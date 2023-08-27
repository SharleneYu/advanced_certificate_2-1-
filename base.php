<?php
date_default_timezone_set("Asia/Taipei");
session_start();

//__DIR__代表檔案所在的當前路徑。連結後設路徑，會成為絕對路徑
include_once __DIR__ ."/controller/Viewer.php";  
include_once __DIR__ ."/controller/User.php";   
include_once __DIR__ ."/controller/News.php";   
include_once __DIR__ ."/controller/Que.php";   


function to($url){
    header("location:".$url);
}

$Viewer = new Viewer;
$User = new User;
$News = new News;
$Que = new Que;





?>