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