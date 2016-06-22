<?php
use Nihcp\Entity\CommonsCreditRequest;

$full_view = elgg_extract('full_view', $vars);

$requests = CommonsCreditRequest::getByUser(CommonsCreditRequest::DRAFT_STATUS);

echo elgg_view('commons_credit_request/overview/components/table', ['full_view' => $full_view, 'requests' => $requests]);