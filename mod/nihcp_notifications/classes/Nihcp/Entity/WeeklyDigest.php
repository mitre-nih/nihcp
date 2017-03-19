<?php
/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 2/24/17
 * Time: 1:21 PM
 */

namespace Nihcp\Entity;

class WeeklyDigest{
    public function __construct(){
        elgg_register_event_handler('init','system',array($this,'init'));
    }

    public function init(){

        elgg_register_plugin_hook_handler('cron', 'weekly', array($this,'weekly_digest_cron'));

        elgg_extend_view("forms/account/settings","core/settings/account/weeklyDigest");
        elgg_register_plugin_hook_handler('usersettings:save','user',array($this,'save_opt_out'));
    }

    public function weekly_digest_cron($hook, $entity_type, $returnvalue, $params){

        $ia = elgg_set_ignore_access();
        //get all Blog entries in the last week
        $time = time()-(60*60*24*7); //any created in the last 7 days
        $options = array(
            'type' => 'object',
            'subtype' => 'blog',
            'limit' => 0,
            'wheres' => 'e.time_created > ' . $time,
        );
        $blog_entries = elgg_get_entities($options);

        //get all Comment entries in the last week
        $commentOptions = array(
            'type' => 'object',
            'subtype' => 'comment',
            'limit' => 0,
            'wheres' => 'e.time_created > ' . $time,
            //'group_by' => 'e.container_guid',
        );
        $comments = elgg_get_entities($commentOptions);
        elgg_set_ignore_access($ia);

        if( (sizeof($blog_entries)>0) || (sizeof($comments)>0) ){
            //now we party?
            $digestContent = array();
            $digestContent["blogs"] = $blog_entries;
            $digestContent["comments"] = $this->sortByConversation($comments);
            $subj = elgg_echo('nihcp_notifications:weekly_digest:email_subj');
            $content = elgg_view("email/weeklyDigest",$digestContent);
            $se = elgg_get_site_entity();
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: '.$se->name.'\r\n';
            $headers .= 'Reply-To: noreply@';

            $userList = elgg_get_entities(array(
                'type' => 'user',
                'limit' => 0,
            ));
            foreach($userList as $user){
                if(!$user->weeklyDigestOptOut){
                    //notify_user($user->getGuid(), elgg_get_site_entity()->getGUID(), $subj, $content);
                    mail($user->email, $subj,$content,$headers);
                }
            }
            
        }else{
            return false;
        }
    }


    public function save_opt_out($hook, $entity_type, $returnvalue, $params){
        //get the opt out checkbox input
        $input = get_input("weekly_digest_opt_out");
        $g = $_REQUEST['guid'];
        $user = get_entity($g);
        if($input === "on") {
            //save it to users metadata
            $user->weeklyDigestOptOut = true;
        }else if($input === "0"){
            elgg_add_subscription($user,"","target-guid");
            $user->weeklyDigestOptOut = false;
        }
        //and then get outta here!
        return $returnvalue;
    }

    public function sortByConversation($comments){
        $retVal = array();
        foreach($comments as $comment){
            $parent = $comment->container_guid;
            $retVal[$parent][] = $comment;
        }

        return $retVal;
    }
}

?>