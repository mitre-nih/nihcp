<?php

namespace Nihcp\Entity;


class SiteTracker{

    public function __construct(){
        elgg_register_event_handler('init','system',array($this,'init'));
    }

    public function init(){
        elgg_register_event_handler('pagehit','object',array($this,'record_page_visit'));

        elgg_register_widget_type('nihcp_page_counter', elgg_echo("nihcp_portal_usage:page_counter"), elgg_echo("nihcp_portal_usage:page_counter:widget:description"));
    }

    public function record_page_visit($event, $object_type, $object){
        $se = elgg_get_site_entity();
        system_log($se,"page hit!");
        $object->pageCounter += 1;
        if(!$object->elggURI){
            $object->elggURI = $_REQUEST["__elgg_uri"];
        }
        //$object->pagecounter += 1?
    }

    //custom in the sense that
    public static function record_custom_visit($title){
        $options = array(
            'type' => 'object',
            'metadata_name_value_pairs' => array(
                'title' => $title,
            ),
        );
        $o = elgg_get_entities_from_metadata($options);
        if(!$o){
            //$o = \ElggObject::
            //$o->title = $title;
            //$o->save();
        }
        //$o->pageCounter += 1;
    }


}