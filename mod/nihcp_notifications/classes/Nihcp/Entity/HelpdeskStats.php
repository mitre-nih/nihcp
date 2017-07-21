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
 

/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 3/14/17
 * Time: 11:54 AM
 */

namespace Nihcp\Entity;

use UserSupportTicket;

class HelpdeskStats{

    public function __construct(){
        elgg_register_event_handler('init', 'system', array($this, 'init'));
    }

    public function init(){
        elgg_register_plugin_hook_handler('cron', 'daily', array($this, 'helpdesk_stats_cron'));

    }

    public function helpdesk_stats_cron($hook, $entity_type, $returnvalue, $params){
        $c = $this->get_open_tickets_in_last_day_count();
        $c2 = $this->get_open_tickets_in_last_week_count();
        $c3 = $this->get_total_open_tickets_count();

        $subj = elgg_echo('nihcp_notifications:notify:helpdesk_stats_subj');
        $new24 = elgg_echo('nihcp_notifications:notify:helpdesk_stats_daily',[$c]);
        $new7 = elgg_echo('nihcp_notifications:notify:helpdesk_stats_weekly',[$c2]);
        $totalOpen = elgg_echo('nihcp_notifications:notify:helpdesk_stats_overall',[$c3]);
        $msg = $new24 . "\n" . $new7 . "\n" . $totalOpen;
        //get helpdesk
        $options = array(
            "type" => "group",
            "limit" => 1,
            "attribute_name_value_pairs" => [
                ["name"=>"name","value"=>\Nihcp\Manager\RoleManager::HELP_ADMIN],
            ],
        );
        $ia = elgg_set_ignore_access();
        $group = elgg_get_entities_from_attributes($options)[0];
        $users = $group->getMembers(array("limit"=>0));
        foreach($users as $user){
            notify_user($user->getGuid(),elgg_get_site_entity()->getGUID(),$subj,$msg);
        }
        elgg_set_ignore_access($ia);


        return $returnvalue;
    }

    public function get_open_tickets_in_last_day_count(){
        $retVal = 0;
        $time = time()-(60*60*24); //any created in the last 24 hours
        $options = array(
            "type" => "object",
            "subtype" => UserSupportTicket::SUBTYPE,
            "full_view" => false,
            "metadata_name_value_pairs" => [
                ["name"=>"status","value" => UserSupportTicket::OPEN,"operand"=>"="],
            ],
            "limit" =>0,
        );
        $ia = elgg_set_ignore_access();
        $results = elgg_get_entities_from_metadata($options);
        foreach($results as $r){
            if ($r->time_created > $time) {
                $retVal += 1;
            }
        }
        elgg_set_ignore_access($ia);

        return $retVal;
    }

    public function get_open_tickets_in_last_week_count(){
        $retVal = 0;
        $time = time()-(60*60*24*7); //any created in the last 7 days
        $mOptions = array(
            "type" => "object",
            "subtype" => UserSupportTicket::SUBTYPE,
            "full_view" => false,
            "metadata_name_value_pairs" => [
                ["name"=>"status","value" => UserSupportTicket::OPEN,"operand"=>"="],
            ],
            "limit" =>0,
        );

        $ia = elgg_set_ignore_access();
        $results = elgg_get_entities_from_metadata($mOptions);
        foreach($results as $r){
            if ($r->time_created > $time) {
                $retVal += 1;
            }
        }
        elgg_set_ignore_access($ia);

        return $retVal;
    }

    public function get_total_open_tickets_count(){
        $options = array(
            "type" => "object",
            "subtype" => UserSupportTicket::SUBTYPE,
            "full_view" => false,
            "metadata_name_value_pairs" => [
                ["name"=>"status","value" => UserSupportTicket::OPEN,"operand"=>"="],
            ],
            "limit" =>0,
            "count" => true,
        );
        $ia = elgg_set_ignore_access();
        $results = elgg_get_entities_from_metadata($options);
        elgg_set_ignore_access($ia);
        return $results;
    }

}