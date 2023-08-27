<?php

include_once "DB.php";

class News extends DB
{

    function __construct()
    {
        parent::__construct('news');     //帶資料庫中的tablename
    }

   
    
    function backend(){
        //建立相關資料的陣列
        $data=[
            // 取得所有user資料
            'rows' => $this->paginate(3),
            'links'=>$this->links(),
            'start'=>($this->links['now']-1)*$this->links['num']+1
        ];
        
        //將要執行的頁面載入
        $this->view("./view/backend/news.php", $data);
    }
}