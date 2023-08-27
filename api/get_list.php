<?php
include_once "../base.php";
// 以po.php中AJAX傳過來的type，做為條件，執行all方法，取出所有type相符的資料，存到$posts中
$posts=$News->all(['type'=>$_GET['type']]);

foreach($posts as $post){
    echo "<div>";
    echo "<a href='Javascript:getPost({$post['id']})'>";
    echo $post['title'];
    echo "</a>";
    echo "</div>";
}