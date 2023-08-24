<?php

class DB{

    //STEP1: construct
    protected $table;
    protected $dsn="mysql:host=localhost; charset=utf8; dbname=db02";
    protected $links;
    protected $pdo;

    function __construct($table)
    {
        $this->table=$table;
        $this->pdo=new PDO($this->dsn, 'root', ''); 
    
    }

    //STEP3: public functions: CRUD in SQL

    function all(...$arg){
        $sql = $this->sql_all(" SELECT * FROM $this->table ", ...$arg);
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    // function all(...$arg){
    //     $sql=$this->sql_all(" SELECT * FROM $this->table ", ...$arg);
    //     return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    // }

    //用在查詢單筆資料查詢，但條件WHERE透過sql_one()準備
    function find($arg){
        $sql = $this->sql_one(" SELECT * FROM $this->table ", $arg);
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    //在在查詢單條件或多條件時，符合條件的筆數。所以用sql_all()完成條件句後再組成SQL語法，執行查詢。
    function count(...$arg){
        $sql = " SELECT count(*) FROM $this->table ";
        $sql = $this->sql_all($sql, ...$arg);
        return $this->pdo->query($sql)->fetchColumn();
    }

    //用在刪除單筆資料，但條件WHERE透過sql_one()準備。例句：DELETE FROM `viewers` WHERE...
    function del($arg){
        $sql = " DELETE FROM $this->table ";
        $sql = $this->sql_one($sql, $arg);
        return $this->pdo->exec($sql);
    }

    function save($arg){
        //有id，為SQL修改：例句UPDATE tablename SET `key1`='val1', `key2`='val2', `key3`='val3' WHERE `id`='1';
        if(isset($arg['id'])){
            $tmp = $this->a2s($arg);
            $sql = " UPDATE $this->table SET ".join(",", $tmp);
            $sql = $sql . " WHERE `id`='{$arg['id']}' ";
        //無id, 為SQL新增：例句INSERT INTO tablename (`key1`, `key2`, `key3`) VALUES ('val1','val2','val3')
        }else{
            $keys=   join("`,`",array_keys($arg));
            $values= join("','",$arg);
            $sql = " INSERT INTO $this->table (`".$keys."`) VALUES ('".$values."')";
        }
        return $this->pdo->exec($sql);
    }

    function max($col, ...$arg){
        return $this->math('max', $col, ...$arg);
    }

    function min($col, ...$arg){
        return $this->math('min', $col, ...$arg);
    }

    function sum($col, ...$arg){
        return $this->math('sum', $col, ...$arg);
    }


    // STEP2:  protected functions: prepare SQL sentences
    //用在把WHERE後數個and，組成字串。例如： [`key1`='val1', `key2`='val2', `key3`='val3']
    protected function a2s($array){
        foreach($array as $key => $value){
            if($key!='id'){
                $tmp[]="`$key`='$value'";
            }
        }
        return $tmp;
    }
 
    //用在組複雜的SQL句：1)先確認有沒有$arg; 2)再確認$arg中有幾個元素，若有2nd個設計為字串可直接接上，1st可能是字串(處理同2nd)或陣列
    protected function sql_all($sql, ...$arg){
        //如果$arg的1st參數是陣列，通常會用在WHERE的條件句(需聯集)
        if(isset($arg[0])){
            if(is_array($arg[0])){
                $tmp=$this->a2s($arg[0]);
                $sql = $sql . " WHERE " .join(" && ",$tmp);
            }else{
                $sql = $sql . $arg[0];
            }
        }
        if(isset($arg[1])){
            $sql = $sql . $arg[1];
        }
        
        return $sql;
    }

    //$arg可能是多條件(陣列)、或單條件(id)。多條件例句為 "SELECT * FROM tablename WHERE `key1`='val1' && `key2`='val2'"
    protected function sql_one($sql, $arg){
        if(is_array($arg)){
            $tmp = $this->a2s($arg);
            $sql = $sql . " WHERE  " . join(" && ",$tmp);
        }else{
            $sql = $sql . " WHERE `id`='$arg' ";       
        }   
        return $sql;
    }
    

    // 用來計算指定條件的值。先用math函式組出句子，再交給其他function執行
    // 例句SELECT 計算功能(欄位名) FROM 資料表 WHERE ...;
    protected function math($math, $col, ...$arg){
        $sql = " SELECT $math($col) FROM $this->table ";
        $sql = $this->sql_all($sql, ...$arg);
        return $this->pdo->query($sql)->fetchColumn();
    }

    // //STEP4: public paginators

    // function view($path, $arg=[]){
    //     extract($arg);  //解包陣列
    //     include($path);  //引進畫面
    // }

    // function paginate($num, $arg=null){
    //     $total= $this->count($arg);
    //     $pages=ceil($total/$num);
    //     $now=$_GET['p']??1;
    //     $start=($now-1)*$num;

    //     $rows= $this->all($arg, " LIMIT $start, $num ");
    //     $this->links=[
    //         'total'=>$total,
    //         'pages'=>$pages,
    //         'now'=>$now,
    //         'start'=>$start,
    //         'rows'=>$rows,
    //         'table'=>$this->table
    //     ];
    // }

    // function links(){
    //     $html='';
    //     if($this->links['now']-1 >=1){
    //         $prev=$this->links['now']-1;
    //         $html .="<a href='?do=$this->table&p=$prev'>&lt;</a>";
    //     }
    
    //     for($i=1; $i<$this->links['pages']; $i++){
    //         //當前頁字24px，其他16px
    //         $fontsize=($i==$this->links['now'])?"24px":"16px";
    //         $html .= "<a href='?do=$this->table&p=$i' style='fontsize=$fontsize'>$i</a>";
    //     }

    //     if($this->links['now']+1 <= $this->links['$pages']){
    //         $next= $this->links['now']+1;
    //         $html .= "<a href='?do=$this->&p=$next'>&gt;</a>";
    //     }
    //     return $html;
    // }


}

// $db=new DB('viewers');
// // $db->save(['date'=>date("Y-m-d"), 'viewer'=>90]);
// // echo "增okay".$db->find(1)['viewer'];
// // echo "<hr>";

// $db->save(['id'=>1, 'viewer'=>70]);
// echo "改okay".$db->find(1)['viewer'];
// // echo "<hr>";

// $db->del(1);
// echo "刪okay".$db->find(1)['viewer'];