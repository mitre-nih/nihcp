<?php/*
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
?>
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
