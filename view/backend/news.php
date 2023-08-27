<form action="./api/news_admin" method="post">
<table class="ct">
    <tr>
        <td>編號</td>
        <td>標題</td>
        <td>顯示</td>
        <td>刪除</td>
    </tr>
   
    <?php
        foreach($rows as $key=>$row){
            ?>
    <tr>
        <td><?=$start+$key;?></td>
        <td><?=$row['title'];?></td>
        <td><input type="checkbox" name="sh[]" value="<?=$row['id'];?>" <?=($row['sh']==1)?"checked":"";?>></td>
        <td><input type="checkbox" name="del[]" value="<?=$row['id'];?>"></td>
    </tr>
    <?php
        }
    ?>
</table>


<!-- ??SYU?? $links是什麼意思，先背起來 -->
<div class="ct"><?=$links;?></div>
</form>