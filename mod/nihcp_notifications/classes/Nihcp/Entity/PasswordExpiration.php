<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
namespace Nihcp\Entity;

/*
* Locks user accounts if they have not changed their password within a certain number of days.
* Provides notice to users before their password expires.
*/
class PasswordExpiration {

    // Number of days before passwords expire
    const MAX_PASSWORD_AGE_DAYS = 60;

    // Number of days before expiration give notice
    const PASSWORD_EXPIRATION_DAYS_NOTICE_1 = 14;
    const PASSWORD_EXPIRATION_DAYS_NOTICE_2 = 3;

    public function __construct() {
        elgg_register_event_handler('init', 'system', array($this, 'init'));
    }

    public function init() {
        elgg_register_plugin_hook_handler('cron', 'daily', array($this,'daily_password_expiration_check'));

    }



    // each day, check every user's password age to see if it has expired
    // some amount of days before expiration, send a notice to the user that password expiration is approaching
    public function daily_password_expiration_check($hook, $entity_type, $returnvalue, $params) {

        $ia = elgg_set_ignore_access();
        $se = elgg_get_site_entity();
        system_log($se,"hit the daily password expiration cron");

        //iterate over all users
        $userList = elgg_get_entities(array(
            'type' => 'user',
            'limit' => 0,
        ));

        foreach($userList as $user) {

            // site admins are exceptions; they dont have expiring passwords
            if (!elgg_is_admin_user($user)) {

                // if user doesn't have this field, populate it with today's time.
                if (empty($user->password_change_time)) {
                    $user->password_change_time = time(); // Unix timestamp in seconds
                    $user->save();
                } else if ($this->is_password_expired($user)) {
                    $this->disable_user_account($user);
                } else if ($this->is_notice_day($user, self::PASSWORD_EXPIRATION_DAYS_NOTICE_1)) {
                    $this->send_notice($user, self::PASSWORD_EXPIRATION_DAYS_NOTICE_1);
                } else if ($this->is_notice_day($user, self::PASSWORD_EXPIRATION_DAYS_NOTICE_2)) {
                    $this->send_notice($user, self::PASSWORD_EXPIRATION_DAYS_NOTICE_2);
                }

            }

        }

        elgg_set_ignore_access($ia);
    }

    public function is_password_expired($user) {
        return $this->get_password_age_in_days($user) > self::MAX_PASSWORD_AGE_DAYS;
    }

    // find out how many days before expiration the password age is
    // if equal to one of the numbers of days notice, return true; otherwise return false
    public function is_notice_day($user, $days_notice) {
        return ( $this->get_days_left_before_password_expiration($user) ) === $days_notice;
    }

    // we are using Elgg user account banning functionality to disable accounts which have expired passwords
    // dont keep banning users that already are banned
    public function disable_user_account($user) {
        if (!$user->isBanned()) {
            elgg_log("User account with email " . $user->email . " has been disabled due to password expiration.");
            $user->ban();
        }
    }

    public function send_notice($user, $days_notice) {
        elgg_log("Sending password expiration " . $days_notice . " days prior notice to " . $user->email . ".");
        mail($user->email, elgg_echo('nihcp_notifications:password_expiration:email:subject'), elgg_echo('nihcp_notifications:password_expiration:email:message', [$days_notice]));
    }

    public function get_password_age_in_days($user) {

        // means new user or user that didn't previously have this property
        // set it to today's time and password age is 0.
        if (empty($user->password_change_time)) {
            $user->password_change_time = time();
            $user->save();
            return 0;
        }

        // relying on DateTime API to account for date inconsistencies like leap years, Daylight Savings Time, etc
        $d1 = new \DateTime();
        $d2 = new \DateTime();

        $time_interval = new \DateInterval('PT'. (time() - $user->password_change_time) . 'S');
        $d2->sub($time_interval);
        $datetime_interval = $d1->diff($d2);

        return $datetime_interval->days;
    }

    public function get_days_left_before_password_expiration($user) {
        return self::MAX_PASSWORD_AGE_DAYS - $this->get_password_age_in_days($user);
    }
}