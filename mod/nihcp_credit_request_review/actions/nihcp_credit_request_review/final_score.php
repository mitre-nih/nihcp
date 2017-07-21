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
 
nihcp_triage_coordinator_gatekeeper();


$action = get_input('action', '', false);

$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
$review_class = htmlspecialchars(get_input('review_class', '', false), ENT_QUOTES, 'UTF-8');
$fs_guid = htmlspecialchars(get_input('fs_guid', '', false), ENT_QUOTES, 'UTF-8');

$ia = elgg_set_ignore_access(true);
$fs_entity = get_entity($fs_guid);



switch ($action) {
    case 'Save':

        // get the object related to this ccreq
        // see if there is existing saved answers entity
        // if so, update it and save
        // else, create a new one and save it
        if (empty($fs_entity)) {
            $fs_entity = new \Nihcp\Entity\FinalScore();
            $fs_entity->status = "reviewed";
            $fs_entity->class = $review_class;
            $fs_entity->save();

            add_entity_relationship($request_guid, \Nihcp\Entity\FinalScore::RELATIONSHIP_CCREQ_TO_FINAL_SCORE, $fs_entity->getGUID());

        }
        $fs_entity->sbr = htmlspecialchars(get_input('mean_class_sbr', '', false), ENT_QUOTES, 'UTF-8');
        $fs_entity->sv = htmlspecialchars(get_input('scientific_value', '', false), ENT_QUOTES, 'UTF-8');
        $fs_entity->cf = htmlspecialchars(get_input('cf', '', false), ENT_QUOTES, 'UTF-8');

        $fs_entity->save();
        break;
    default:
        // do nothing
        break;
}

elgg_set_ignore_access($ia);

forward(elgg_get_site_url() . "nihcp_credit_request_review/final-score-overview/$request_guid");
