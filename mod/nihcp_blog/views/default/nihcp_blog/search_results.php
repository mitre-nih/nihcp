<?php


$search_term = sanitize_string(elgg_extract('search_term', $vars));


$options = array(
    'type' => 'object',
    'subtype' => 'blog',
    'preload_owners' => true,
    'distinct' => false,
);

$entities = elgg_get_entities($options);
$results = array();

echo "<h4>Search results for: \"$search_term\"</h4>";

foreach($entities as $entity) {

    $title = strtolower($entity->title);
    $description = strtolower($entity->description);

    $search_term_lowercase = strtolower($search_term);

    if (strpos($title, $search_term_lowercase) !== false || strpos($description, $search_term_lowercase) !== false) {
        $results[] = $entity;
    }
}


echo elgg_view_entity_list($results, array(
    'no_results' => elgg_echo('blog:none'),
    'full_view' => false,
));
