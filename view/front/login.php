<fieldset>
    <legend>會員登入</legend>
    <!-- 過去用form表單來傳值，但這個練習改用AJAX來傳值 -->
    <table>
        <tr>
            <td>帳號</td>
            <td>
                <input type="text" name="acc" id="acc">
            </td>
        </tr>
        <tr>
            <td>密碼</td>
            <td>
                <input type="password" name="pw" id="pw">
            </td>
        </tr>
        <tr>
            <td>
                <input type="button" value="登入" onclick=login()>
                <input type="button" value="清除" onclick=clean()>
            </td>
            <td>
                <a href="?do=forgot">忘記密碼</a>
                <a href="?do=reg">尚未註冊</a>
            </td>
        </tr>
    </table>
</fieldset>

<script>
    // 因為題目要告知登入者，是帳、還是密錯，所以用AJAX以物件傳值較為簡單
    function login(){
        let user={
            acc:$('#acc').val(),
            pw:$('#pw').val()
        }
        
        $.post('./api/check_acc.php',user,(res)=>{
        
            //抓到php物件方法$User透過api/check_acc傳回的值，將其轉為字串(因為有時會是數字)
            switch (parseInt(res)) {
                case 0:
                    alert("查無帳號");
                    break;
                case 1:
                    if(user.acc=='admin'){
                        location.href='backend.php';
                    }else{
                        location.href='index.php';
                    }
                    break;
                case 2:
                    alert("密碼錯誤");
                    break;
            
                default:
                    break;
            }



        })
    }

    //點選時指定id的值設為空
    // function clean(){
    //     $('#acc, #pw').val("");   
    // }

</script>