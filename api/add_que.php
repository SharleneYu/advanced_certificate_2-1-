<?php include_once "../base.php";

// 若有主題再處理
if(isset($_POST['subject'])){
    // 實務上要再檢查主題是否重覆，檢定考省略
    $Que->save([
       'text'=>$_POST['subject'],
       'vote'=>0,
       'subject_id'=>0 
    ]);

    // 若選項存在，再將選項存入資料表
    if(isset($_POST['text'])){
        $subject_id = $Que->find(['text'=>$_POST['subject']])['id'];
        foreach($_POST['text'] as $option){
            $Que->save([
                'text'=>$option,
                'vote'=>0,
                'subject_id'=>$subject_id
            ]);
        }
    }
}
  

to("../backend.php?do=que");

