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
 * Date: 2/24/17
 * Time: 1:21 PM
 */

namespace Nihcp\Entity;

use Elgg\Queue\DatabaseQueue;

class WeeklyDigest{

    private static $QUEUE_NAME = "weeklyDigest";

    public function __construct(){
        elgg_register_event_handler('init','system',array($this,'init'));
        $this->queue = new DatabaseQueue(WeeklyDigest::$QUEUE_NAME,_elgg_services()->db);
    }

    public function init(){
        elgg_register_plugin_hook_handler('cron', 'weekly', array($this,'weekly_digest_cron'));
        elgg_register_plugin_hook_handler('cron', 'minute', array($this,'minute_cron'));
        elgg_extend_view("forms/account/settings","core/settings/account/weeklyDigest");
        elgg_register_plugin_hook_handler('usersettings:save','user',array($this,'save_opt_out'));
    }//init

    //once a week, generate digest content.  If we have digest content, toss it into a queue to be handled
    public function weekly_digest_cron($hook, $entity_type, $returnvalue, $params){

        $subj = elgg_echo('nihcp_notifications:weekly_digest:email_subj');
        $msg = $this->generate_digest_content();
        $se = elgg_get_site_entity();
        system_log($se,"hit the weekly digest cron");

        //$msg will be the message, or false.
        if($msg){
            $ia = elgg_set_ignore_access();
            system_log($se,"preparing a weekly digest queue");
            system_log($se,"queue length prior: " . $this->queue->size());

            $userList = elgg_get_entities(array(
                'type' => 'user',
                'limit' => 0,
            ));

            foreach($userList as $user){
                //only care about users who have not opted out
                if(!$user->weeklyDigestOptOut){
                    $data = array(
                        "user" => $user,
                        "subj" => $subj,
                        "msg" => $msg
                    );
                    $this->queue->enqueue($data);
                }
            }
            elgg_set_ignore_access($ia);
        }//if digest content
        system_log($se,"weekly digest queue length: " . $this->queue->size());

    }//weekly_cron


    //once a minute, handle the digest queue that's been set up in the weekly
    public function minute_cron($hook, $entity_type, $returnvalue, $params){
        $s = $this->queue->size();

        //only want to bother if there's anything in the queue.
        if($s > 0) {
            $stop_time = time() + 45; //we're gonna use this to stop the queue process
            $ia = elgg_set_ignore_access();
            $se = elgg_get_site_entity();
            $headers = 'From: ' . $se->email . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            system_log($se,"we're gonna loop!");
            while( (time() < $stop_time) and ($this->queue->size() > 0) ){
                $d = $this->queue->dequeue();
                system_log($se,"sending email to: " . $d["user"]->email);
                mail($d["user"]->email, $d["subj"], $d["msg"], $headers);
            }
            elgg_set_ignore_access($ia);
        }
        system_log($se,"queue length after minute cron: " . $this->queue->size());

    }//weekly_cron

    //return content, or false if empty
    public function generate_digest_content(){
        $retVal = false;
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
        );
        $comments = elgg_get_entities($commentOptions);
        //elgg_set_ignore_access($ia);

        if( (sizeof($blog_entries)>0) || (sizeof($comments)>0) ) {
            //now we party?
            $digestContent = array();
            $digestContent["blogs"] = $blog_entries;
            $digestContent["comments"] = $this->sortByConversation($comments);
            $retVal = elgg_view("email/weeklyDigest", $digestContent);
        }
        elgg_set_ignore_access($ia);
        return $retVal;
    }//generate digest content

    public function save_opt_out($hook, $entity_type, $returnvalue, $params){
        //get the opt out checkbox input
        $input = get_input("weekly_digest_opt_out");
        $g = $_REQUEST['guid'];
        $user = get_entity($g);
        if($input === "on") {
            //save it to users metadata
            $user->weeklyDigestOptOut = true;
        }else if($input === "0"){
            $user->weeklyDigestOptOut = false;
        }
        //and then get outta here!
        return $returnvalue;
    }//save_opt_out

    public function sortByConversation($comments){
        $retVal = array();
        foreach($comments as $comment){
            $parent = $comment->container_guid;
            $retVal[$parent][] = $comment;
        }

        return $retVal;
    }//sortByConversation

}


?>
