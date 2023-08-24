<?php
date_default_timezone_set("Asia/Taipei");
session_start();

//__DIR__代表檔案所在的當前路徑。連結後設路徑，會成為絕對路徑
include __DIR__ ."/controller/Viewer.php";  



$Viewer = new Viewer;

?>