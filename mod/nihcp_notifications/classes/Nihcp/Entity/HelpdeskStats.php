<?php
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