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


class SiteTracker{

    public function __construct(){
        elgg_register_event_handler('init','system',array($this,'init'));
    }

    public function init(){
        elgg_register_event_handler('pagehit','object',array($this,'record_page_visit'));
        elgg_register_event_handler('pagehit','faq',array($this,'handle_faq'));
        elgg_register_event_handler('pagehit','help_center',array($this,'handle_help_center'));
        elgg_register_event_handler('pagehit','user_manual',array($this,'handle_user_manual'));

        elgg_register_widget_type('nihcp_page_counter', elgg_echo("nihcp_portal_usage:page_counter"), elgg_echo("nihcp_portal_usage:page_counter:widget:description"));
    }

    public function record_page_visit($event, $object_type, $object){
        //$se = elgg_get_site_entity();
        //system_log($se,"page hit!");
        $object->annotate("pageHit", 1, ACCESS_LOGGED_IN);

        //$count = $object->getAnnotationsSum("pageHit");
        //$rslt = $this->get_visit_stats();
        if(!$object->elggURI){
            $object->elggURI = $_REQUEST["__elgg_uri"];
        }
    }//record_page_visit

    public function get_visit_stats($limit=10){
        $options = array(
            'calculation' => "sum",
            'limit' => $limit,
            'annotation_names' => array('pageHit'),
        );
        $results = elgg_get_entities_from_annotation_calculation($options);
        return $results;
        //$nop = "";
    }

    public function handle_faq($event,$object_type,$object){
        //if faq object exists, annotate it
        $options = array(
            'type' => 'object',
            'limit' => 1,
            "attribute_name_value_pairs" => [
                ["name"=>"title","value"=>'FAQ Listing Page'],
            ],

        );
        $ia = elgg_set_ignore_access();
        $obj = elgg_get_entities_from_attributes($options)[0];
        if($obj){
            $obj->annotate("pageHit",1,ACCESS_LOGGED_IN);
        }else{
            $o = new \ElggObject();
            $o->title = "FAQ Listing Page";
            $o->save();
            $o->elggURI = elgg_get_site_url() . "user_support/faq";
            $o->annotate("pageHit",1,ACCESS_LOGGED_IN);
        }
        elgg_set_ignore_access($ia);
        //else create one and anotate
    }

    public function handle_help_center($event,$object_type,$object){
        //if faq object exists, annotate it
        $options = array(
            'type' => 'object',
            'limit' => 1,
            "attribute_name_value_pairs" => [
                ["name"=>"title","value"=>'Help Center'],
            ],

        );
        $ia = elgg_set_ignore_access();
        $obj = elgg_get_entities_from_attributes($options)[0];
        if($obj){
            $obj->annotate("pageHit",1,ACCESS_LOGGED_IN);
        }else{
            $o = new \ElggObject();
            $o->title = "Help Center";
            $o->save();
            $o->elggURI = elgg_get_site_url() . "user_support/help_center";
            $o->annotate("pageHit",1,ACCESS_LOGGED_IN);
        }
        elgg_set_ignore_access($ia);

    }

    public function handle_user_manual($event,$object_type,$object){
        //if faq object exists, annotate it
        $options = array(
            'type' => 'object',
            'limit' => 1,
            "attribute_name_value_pairs" => [
                ["name"=>"title","value"=>'User Manual'],
            ],

        );
        $ia = elgg_set_ignore_access();
        $obj = elgg_get_entities_from_attributes($options)[0];
        if($obj){
            $obj->annotate("pageHit",1,ACCESS_LOGGED_IN);
        }else{
            $o = new \ElggObject();
            $o->title = "User Manual";
            $o->save();
            $o->elggURI = elgg_get_site_url() . "nihcp_commons_credit_request/investigator-portal-user-manual";
            $o->annotate("pageHit",1,ACCESS_LOGGED_IN);
        }
        elgg_set_ignore_access($ia);
    }

}