<?php

class DB{
protected $table;
protected $dsn="mysql:host=localhost;charset=utf8; dbname=db04";
protected $links;
protected $pdo;

function __construct($table)
{
    $this->table=$table;
    $this->pdo= new PDO($this->dsn, 'root', '');
}


//function

function all(...$arg){
    $sql = $this-> sql_all(" SELECT * FROM $this->table ", ...$arg);
    return $this-> pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}




//tools  (SYU:要弄懂why)
protected function a2s($array){

    foreach($array as $key => $val){
        if($key !='id'){
            $tmp[]= "`$key`='$val'";
        }
    }
    return $tmp;
}


protected function sql_all($sql, ...$arg){
    if(!empty($arg)){
        if(isset($arg[0])){
            if(is_array($arg)){
                $tmp=$this->a2s($arg[0]);
                $sql=$sql. " WHERE " . join(" && ", $tmp);
            }
        }else{
            $sql = $sql . $arg[0];
        }
        if(isset($arg[1])){
            $sql = $sql . $arg[1];
        }
    }
    return $sql;
}

function sql_one($sql, $arg){
    if(is_array($arg)){
        $tmp=$this->a2s($arg);
        $sql= $sql . " WHERE " . join(" && ", $tmp);
    }else{
        $sql = $sql . " WHERE `id` = '$arg'"; 
    }
    return $sql;
}


function count(...$arg){
    $sql = $this -> sql_all( " SELECT COUNT(*) FROM $this->table ", ...$arg );
    return $this->pdo->query($sql)->fetchColumn();
}




//view



}

?>