<?php

include_once "DB.php";

class Que extends DB
{

    function __construct()
    {
        parent::__construct('que');     //帶資料庫中的tablename
    }

   
    function backend(){

        //將要執行的頁面載入
        $this->view("./view/backend/que.php");
    }
    
}