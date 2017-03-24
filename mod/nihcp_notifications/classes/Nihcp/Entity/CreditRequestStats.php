<?php


namespace Nihcp\Entity;

class CreditRequestStats{
    public function __construct(){
        elgg_register_event_handler('init', 'system', array($this, 'init'));
    }

    public function init(){
        elgg_register_plugin_hook_handler('cron', 'daily', array($this, 'ccreq_stats_cron'));

    }


    public function ccreq_stats_cron($hook, $entity_type, $returnvalue, $params){
        $status = "";

        //$new = $this->get_new_submissions_count();
        //$nr = $this->get_not_reviewed_in_last_week_count();
        //$tnr = $this->get_total_not_reviewed_count();
        $draftCount = $this->get_total_drafsts();
        $drafts = $this->get_drafts();

        $subj = elgg_echo('nihcp_notifications:notify:ccreq_stats_subj');
        //$new24 = elgg_echo('nihcp_notifications:notify:ccreq_stats_daily', [$new]);
        //$noReview = elgg_echo('nihcp_notifications:notify:ccreq_stats_weekly', [$nr]);
        //$totalNoReview = elgg_echo('nihcp_notifications:notify:ccreq_stats_overall', [$tnr]);
        //$msg = $new24 . "\n" . $noReview . "\n" . $totalNoReview;
        $msg = elgg_echo('nihcp_notifications:notify:ccreq_stats_draft',[$draftCount]);
        $msg .= "\n";
        $ia = elgg_set_ignore_access();
        foreach($drafts as $draft){
            $msg .= $draft->project_title . "\n";
        }
        elgg_set_ignore_access($ia);

        $options = array(
            "type" => "group",
            "limit" => 1,
            "attribute_name_value_pairs" => [
                ["name" => "name", "value" => \Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR],
            ],
        );
        $ia = elgg_set_ignore_access();
        $group = elgg_get_entities_from_attributes($options)[0];
        $users = $group->getMembers(array("limit" => 0));
        foreach ($users as $user) {
            notify_user($user->getGuid(), elgg_get_site_entity()->getGUID(), $subj, $msg);
        }
        elgg_set_ignore_access($ia);

        return $returnvalue . $status;
    }

    public function get_new_submissions_count(){
        $retVal = 0;
        $time = time()-(60*60*24); //any created in the last 24 hours
        $request = CommonsCreditRequest::getByCycle();

        //I tried doing a custom elgg_get_entities_by_metadata call with the time_created comparison as a name-value pair
        //but it wasn't working for whatever reason (likely my error in calling it).  Rather than spend more time debugging
        //it I'll just 'brute force' it.  Running once a day shouldn't be a big deal.
        $ia = elgg_set_ignore_access();
        foreach($request as $r){
            if($r->status == CommonsCreditRequest::SUBMITTED_STATUS) {
                if ($r->time_created > $time) {
                    $retVal += 1;
                }
            }
        }
        elgg_set_ignore_access($ia);
        return $retVal;
    }


    public function get_not_reviewed_in_last_week_count(){
        $retVal = 0;
        $request = CommonsCreditRequest::getByCycle();
        $time = time()-(60*60*24*7); //any created in the last 24 hours
        $ia = elgg_set_ignore_access();
        foreach($request as $r){
            if($r->status === \Nihcp\Entity\CommonsCreditRequest::SUBMITTED_STATUS) {
                if ($r->time_created > $time) {
                    $retVal += 1;
                }
            }
        }
        elgg_set_ignore_access($ia);

        return $retVal;
    }

    public function get_total_not_reviewed_count(){
        $ia = elgg_set_ignore_access();
        $requests = elgg_get_entities_from_metadata([
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
            'limit' => 0,
            'count' => true,
            'metadata_name_value_pairs' => [
                ['name' => 'status', 'value' => CommonsCreditRequest::SUBMITTED_STATUS, 'operand' => "="]
            ],
        ]);
        elgg_set_ignore_access($ia);
        return $requests;
    }

    public function get_total_drafsts(){
        $ia = elgg_set_ignore_access();
        $requests = elgg_get_entities_from_metadata([
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
            'limit' => 0,
            'count' => true,
            'metadata_name_value_pairs' => [
                ['name' => 'status', 'value' => CommonsCreditRequest::DRAFT_STATUS, 'operand' => "="]
            ],
        ]);
        elgg_set_ignore_access($ia);
        return $requests;
    }

    public function get_drafts(){
        $ia = elgg_set_ignore_access();
        $requests = elgg_get_entities_from_metadata([
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
            'limit' => 0,
            'metadata_name_value_pairs' => [
                ['name' => 'status', 'value' => CommonsCreditRequest::DRAFT_STATUS, 'operand' => "="]
            ],
        ]);
        elgg_set_ignore_access($ia);
        return $requests;
    }
}//class: Credit Request Stats

?>
