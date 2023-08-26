<fieldset>
    <legend >會員註冊</legend>
    <div style="color:red">*請設定您要註冊的帳號及密碼（最長12個字元）</div>
    <table>
        <tr>
            <td>Step1: 登入帳號</td>
            <td><input type="text" name="acc" id="acc"></td>
        </tr>
        <tr>
            <td>Step2: 登入密碼</td>
            <td><input type="password" name="pw" id="pw"></td>
        </tr>
        <tr>
            <td>Step3: 再次確認密碼</td>
            <td><input type="password" name="pw2" id="pw2"></td>
        </tr>
        <tr>
            <td>Step4: 信箱(忘記密碼時使用)</td>
            <td><input type="text" name="email" id="email"></td>
        </tr>
        <tr>
            <td>
                <button onclick=reg()>註冊</button>
                <button onclick=clean()>清除</button>
            </td>
        </tr>
    </table>
</fieldset>

<script>
    function reg(){
        let info={
            acc:$('#acc').val(),
            pw:$('#pw').val(),
            pw2:$('#pw2').val(),
            email:$('#email').val(),
        }        

        if(info.acc=='' || info.pw=='' || info.pw2=='' || info.email==''){
            alert("不可空白");
        }else if(info.pw !=info.pw2){
            alert("密碼錯誤");
        }else{
            // 將輸入的帳號送出給api/check_acc.php檢查。
            $.post("./api/check_acc.php", {acc:info.acc}, (res)=>{
                if(parseInt(res)!==0){
                    alert("帳號重覆");
                }else{
                    $.post("./api/reg.php", info, ()=>{
                        alert("註冊完成，歡迎加入");
                    } )
                }
            });
        
        }

    }

</script>
