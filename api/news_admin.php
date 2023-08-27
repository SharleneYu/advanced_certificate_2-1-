<?php include_once "../base.php";

// 實務上只要是表單傳過來的資料，最好先檢查if(isset($_POST['id']))...。因為檢定求速度，所以省略

foreach($_POST['id'] as $id){
    // 傳值過來有name=del的，同時$id有在$_POST的陣列裡，再進行刪除動作
    if(isset($_POST['del'])  && in_array($id, $_POST['del'])){
        $News->del($id);   
    }else{
        $row=$News->find($id);
        // 傳值過來有name=sh的，同時$id有在$_POST的陣列裡，顯示狀態設為1；反之為0
        $row['sh']=(isset($_POST['id']) && in_array($id, $_POST['sh']))?1:0;
        $News->save($row);
    }
}

to("../backend.php?do=news");

