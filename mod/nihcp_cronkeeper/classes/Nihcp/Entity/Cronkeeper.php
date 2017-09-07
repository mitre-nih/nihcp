<?php
/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 8/11/17
 * Time: 3:00 PM
 */

namespace Nihcp\Entity;


class Cronkeeper{

    public function __construct(){
        elgg_register_event_handler('init','system',array($this,'init'));
    }

    public function init(){
        $se = elgg_get_site_entity();
        //unregister default cron page handler
        elgg_unregister_page_handler('cron','_elgg_cron_page_handler');
        //register our own custom cron handler
        elgg_register_page_handler('cron',array($this,'cronkeeper_cron_page_handler'));

        //if $ip is "", the setting has never been set, so we'll default to yes
        $ip = elgg_get_plugin_setting('limit_to_local', 'nihcp_cronkeeper');
        if(!$ip){
            elgg_set_plugin_setting('limit_to_local','yes','nihcp_cronkeeper');
        }

    }

    public function cronkeeper_cron_page_handler($page){
        $retVal = false;
        if($this->is_authorized()){
            $retVal =_elgg_cron_page_handler($page);
        }
        return $retVal;

    }//cronkeeper_cron_page_handler

    public function is_authorized(){
        $retVal = false;

        //handle localhost checking
        //limit to local might be better served with a different name.
        //It's not an exclusive limit if an api key/value is set.
        $ll = elgg_get_plugin_setting('limit_to_local', 'nihcp_cronkeeper');
        if($ll == "yes") {
            if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
                $retVal = true;
            }
        }

        //handle api key checking
        //note these conditions are an OR, not an AND
        $keyName = elgg_get_plugin_setting('key_name', 'nihcp_cronkeeper');
        $keyValue = elgg_get_plugin_setting('key_value', 'nihcp_cronkeeper');
        if($keyName){
            $givenKey = sanitise_string($_REQUEST["$keyName"]);
            if($keyValue === $givenKey){
                $retVal = true;
            }
        }
        return $retVal;
    }


}//class Cronkeeper