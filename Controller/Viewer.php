<?php

include "DB.php";

class Viewer extends DB
{

    function __construct()
    {
        parent::__construct('viewers');     //帶資料庫中的tablename
    }

    // 取得今日日期，搜尋資料表中是否有今日日期，若無直接存1；若有今日，在viewer人數+1存入並取值
    function todayViewer(){
        $today=date("Y-m-d");
        if(!isset($_SESSION['viewer'])){
            $chk=$this->count(['date'=>$today]);
            if($chk){
                $row=$this->find(['date'=>$today]);
                $row['viewer']++;
                $this->save($row);
                $_SESSION['viewer']=1;
                return $row['viewer'];       
            }else{
                $this->save(['date'=>$today, 'viewer'=>1]);
                $_SESSION['viewer']=1;
                return 1;
            }
        }else{
            return $this->find(['date'=>$today])['viewer'];
        }
    }

    function totalViewer(){
        return $this->sum('viewer');
    }

}