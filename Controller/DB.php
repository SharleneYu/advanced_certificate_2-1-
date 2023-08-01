<?php

class DB{
    protected $table;
    protected $dsn="mysql:host=localhost; charset=utf-8; dbname=db02";
    protected $pdo;
    protected $links;

    function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn, 'root','');
       
    }


    // STEP3 FUNCTION: CRUD
    function all(...$arg){
        $sql= $this->sql_all(" SELECT * FROM $this->table ", ...$arg);
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function count(...$arg){
        $sql= $this->sql_all(" SELECT count(*) FROM $this->table ", ...$arg);
        return $this->pdo->query($sql)->fetchColumn();
    }

    function find(...$arg){
        $sql= $this->sql_one(" SELECT * FROM $this->table ", $arg);
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    function del(...$arg){
        $sql= $this->sql_one(" DELETE * FROM $this->table ", $arg);
        return $this->pdo->exec($sql);
    }

    function save($arg){
        // 有id，為更新
        if(isset($arg['id'])){
            $tmp=$this->a2s($arg);
            $sql=" UPDATE $this->table SET " . join(" , ", $tmp)
                . " WHERE `id`='{$arg['id']} ";
        //無id, 為新增
        }else{
            $keys  = array_keys($arg);
            $values=$arg;
            $sql = " INSERT INTO $this->table (`".join("`,`",$keys)."`) VALUES ('".join("','",$values)."') ";
        }
        return $this->pdo->exec($sql);
    }

    function max($col,...$arg){
        return $this->math('max', $col, ...$arg);
    }
    function min($col,...$arg){
        return $this->math('min', $col, ...$arg);
    }
    function sum($col,...$arg){
        return $this->math('sum', $col, ...$arg);
    }


    // STEP2 TOOLS: 建立類別內function，用來組SQL語法用的字串：sql_all、sql_one、math、a2s
    protected function a2s($array){
        foreach($array as $key => $val){
            // 將非id(因是auto increment)，以key=val的方式存到陣列
            if($key!='id'){
               $tmp[]="`$key`='$val'";
            }
        }
        return $tmp;
    }

    protected function sql_all($sql,...$arg){
        if(!empty($arg)){
            if(isset($arg[0])){
                //如果$arg的1st參數是陣列，通常會用在WHERE的條件句(需聯集)
                if(is_array($arg)){
                    $tmp=$this->a2s($arg[0]);
                    $sql=$sql ." WHERE ". join(" && ", $tmp);
                //如果$arg的1st參數非陣列，當成一般SQL語
                }else{
                    $sql = $sql . $arg[0];
                }
            }
            //如果$arg的2nd參數，一定會是字串。因為陣列會放在1st參數
            if(isset($arg[1])){
                $sql = $sql . $arg[1];
            }
        }
        return $sql;
    }

    protected function sql_one($sql,$arg){   //$arg不是陣列、就是ID
        if(is_array($arg)){
            $tmp = $this->a2s($arg);
            $sql = $sql . " WHERE " . join(" && ", $tmp);
        }else{
            $sql = $sql . " WHERE `id`='$arg'";
        }
        return $sql;
    }

    protected function math($math, $col, ...$arg){
        $sql=" SELECT $math($col) FROM $this->table ";
        $sql = $this->sql_all($sql, ...$arg);

        return $this->pdo->query($sql)->fetchColumn();
    }


    //view
    function view($path, $arg=[]){
        extract($arg);  //解包陣列
        include($path);  //引進畫面
    }

    function paginate($num, $arg=null){
        $total= $this->count($arg);
        $pages=ceil($total/$num);
        $now=$_GET['p']??1;
        $start=($now-1)*$num;

        $rows= $this->all($arg, " LIMIT $start, $num ");
        $this->links=[
            'total'=>$total,
            'pages'=>$pages,
            'now'=>$now,
            'start'=>$start,
            'rows'=>$rows,
            'table'=>$this->table
        ];
    
    }

    function links(){
        $html='';
        if($this->links['now']-1 >=1){
            $prev=$this->links['now']-1;
            $html .= "<a href='?do=$this->table&p=$prev'> &lt; </a>";
        }

        for($i=1; $i<$this->links['pages'];$i++){
            //當前頁碼的字型為24px、其他頁碼16px;
            $fontsize=($i==$this->links['now'])?"24px":"16px";
            $html .= "<a href='?do=$this->table&p=$i' style='font-size: $fontsize;'> $i </a>";            
        }

        if($this->links['now']+1  <=  $this->links['$pages'] ){
            $next=$this->links['now']+1;
            $html .= "<a href='?do=$this->table&p=$next'> &gt; </a>";
        }
        return $html;
    }

}

?>