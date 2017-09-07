<?php
/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 8/11/17
 * Time: 2:59 PM
 */



$cronkeeper = new Nihcp\Entity\Cronkeeper();

elgg_register_event_handler('init', 'system', 'cronkeeper_init');

function cronkeeper_init(){

}

?>