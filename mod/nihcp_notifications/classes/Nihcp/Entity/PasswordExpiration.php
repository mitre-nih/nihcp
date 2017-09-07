<?php

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