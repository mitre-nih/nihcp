<html>
<head>
    <style>
        .odd{
            background-color: lightgrey;

        }
        .even{
            background-color: white;
        }
        .blog{
            font-size:22pt;
        }
        .title{
            font-size:17pt;
        }
    </style>
</head>
<body>
<?php
echo "<span class='blog'>";
echo elgg_echo('nihcp_notifications:weekly_digest:title') . "<br>";
echo "</span><br>";
$count = sizeof($vars['blogs']);
echo "<span class='title'>$count new conversation(s) were started!</span><br><br>";
if($count >= 10) {
    $allURL = elgg_get_site_url() . "/blog/all";
    echo "You can discover these new discussions <a href='$allURL'>here</a><br>";
}else{

//$vars['blogs']
    foreach ($vars['blogs'] as $blog) {
        $title = $blog->title;
        $poster = get_entity($blog->owner_guid);
        $bUrl = $blog->getURL();
        echo "$poster->name created the conversation : <a href='$bUrl'>$title<a> <br><br>";
    }
}

$commentCount = sizeof($vars['comments']);
if($commentCount > 0) {
    echo "<br><span class='title'>Here's what people were talking about:</span><br><br>";
    //$vars['comments']
    $odd = "style=\"background-color: #20558a;color:white\"";
    $even = "style=\"background-color: white\"";
//$style = $odd;
    $style = "odd";
//foreach($vars['comments'] as $comment){
    foreach($vars['comments'] as $convo){
        $convoLength = sizeof($convo);
        if($convoLength >= 5) {
            $parentBlog = get_entity($convo[0]->container_guid);
            echo "<a href='$bUrl'>$parentBlog->title </a> has seen a flurry of conversation, $convoLength posts in the last week!";
        }else {
            foreach($convo as $comment) {
                $cPoster = get_entity($comment->owner_guid);
                $parentBlog = get_entity($comment->container_guid);
                $content = $comment->description;
                $bUrl = $parentBlog->getURL();

                echo "<div class='$style'>";
                echo "$cPoster->name commented on <a href='$bUrl'>$parentBlog->title </a>: $content";
                echo "</div>";
                if ($style === "odd") {
                    $style = "even";
                } else {
                    $style = "odd";
                }
            }
        }
    }
}



?>
</body>
</html>
