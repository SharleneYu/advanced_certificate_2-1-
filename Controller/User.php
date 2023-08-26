<?php

include_once "DB.php";

class User extends DB
{

    function __construct()
    {
        parent::__construct('users');     //帶資料庫中的tablename
    }

    
    function chk_acc($user){
        // 檢查$user的帳號是否存在
        $chk=$this->count(['acc'=>$user['acc']]);
        if($chk>0){
            // 若有此帳號，再繼續檢查輸入的密碼是否存在
            $chk=$this->chk_pw($user);
            if($chk>0){
                $_SESSION['user']=$user['acc'];
                return 1;  //帳密皆正確
            }else{
                return 2;  //密碼錯誤
            }
        }else{
            return 0;  //帳號錯誤
        }
    
    }

    function chk_pw($user){
        //count($user)= WHERE `acc`='$user['acc]' && `pw`='$user['pw']'
        return $this->count($user);
    }
    

}