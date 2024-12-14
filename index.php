<?php
require_once('db.php');

$_SESSION['error'] = $_SESSION['error'] ?? 0;

if($_SESSION['error'] >= 3){
    $_SESSION['error'] = 0;
    load('error.php');
}

if(isset($_GET['error'])){
    $_SESSION['error'] ++;
    if(isset($_SESSION['tmp'])){
        ins('logs',[
            'user_id'=>$_SESSION['tmp']['id'],
            'action'=>'登入',
            'success'=>'失敗'
        ]);
        unset($_SESSION['tmp']);
    }
    load('index.php');
}

if(isset($_POST['submit'])){
    $user = sel('users',['account'=>$_POST['account']]);

    if(empty($user)){
        alert('帳號有誤','?error');
    }
    $_SESSION['tmp'] = $user;
    if($_POST['password'] != $user['password']){
        alert('密碼有誤','?error');
    }
    header("location:log.php");
    // if($_SESSION['captcha'] != join('',$_POST['captcha'])){
    //     alert('圖形驗證碼有誤','?error');
    // }
    // alert('二次驗證');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('header.php') ?>
</head>
<body class="login">
    <div class="container mt-5">
        <div class="col-6 offset-3 bg-card rounded shadow p-5">
            <h2 class="text-center">
                咖啡商品展示系統
            </h2>
            <hr>
            <!-- /< ? php// if(!isset($_SESSION['tmp'])){?> -->
                <form action="" method="post">

                    <label for="" class="mt-2">帳號</label>
                    <input type="text" class="form-control" required name="account">
                    <label for="" class="mt-2">密碼</label>
                    <input type="password" class="form-control" required name="password">
                    <!-- <label for="" class="mt-2">圖形驗證碼</label>
                    <div class="row">
                        <div class="col-8 captcha"></div>
                        <div class="col-4">
                            <button class="btn btn-secondary w-100" type="button" onclick="resetImg()">重新產生</button>
                        </div>
                    </div> -->
                    <!-- <label for="" class="mt-2">輸入框提示規則 : <span class="rand"></span></label> -->
                    <!-- <div class="row">
                        <div class="col">
                            <input type="text" class="form-control text-center ans" name="captcha[]" maxLength="1" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control text-center ans" name="captcha[]" maxLength="1" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control text-center ans" name="captcha[]" maxLength="1" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control text-center ans" name="captcha[]" maxLength="1" required>
                        </div>
                    </div> -->
                    <div class="row mt-3">
                        <div class="col">
                            <button class="w-100 btn btn-outline-secondary" type="reset">重設</button>
                        </div>
                        <div class="col">
                            <button class="w-100 btn btn-secondary" name="submit">送出</button>
                        </div>
                    </div>
                </form>
            <?php //  }else{?>
                <!-- <h4 class="text-center">
                    連成 2 格水平線或垂直線
                </h4>
                <div class="verify mx-auto">
                    <div class="block" id="1"></div>
                    <div class="block" id="2"></div>
                    <div class="block" id="3"></div>
                    <div class="block" id="4"></div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-secondary" onclick="verify()">確定</button>
                </div> -->
            <?php // }?>
        </div>
    </div>
    <script>
        function verify(){
            let array = [];
            $('.click').each((i,e)=>{
                array.push(e.id)
            })
            let check  = ['12','34','13','24'].some(e => array.join('') == e)
            if(check){
                alert('登入成功')
                location.href = 'log.php'
            }else{
                alert('二次驗證有誤')
                location.href = '?error'
            }
        }
        $('.block').click(function(){
            $(this).toggleClass('click')
        })
        resetImg()
        function resetImg(){
            $('.captcha').empty()
            $('.ans').val('')
            fetch('rand.php')
            .then(res => res.json())
            .then(res => {
                $('.rand').text(res[1] ? '由大到小' : '由小到大')
                res[0].forEach(e => {
                    let img = new Image()
                    img.src = 'svg.php?text='+e
                    img.id = e
                    img.style.zIndex = 100
                    $('.captcha').append(img)
                    $(img).draggable({
                        helper: 'clone'
                    })
                });
            })
        }
        $('.ans').droppable({
            drop:function(e,u){
                $(this).val(u.draggable[0].id)
            }
        })
    </script>
</body>
</html>