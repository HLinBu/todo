<?php
require_once('db.php');

if(isset($_SESSION['tmp'])){
    $_SESSION['user'] = $_SESSION['tmp'];
    $_SESSION['error'] = 0;
    unset($_SESSION['tmp']);
    ins('logs',[
        'user_id'=>$_SESSION['user']['id'],
        'action'=>'登入 ',
        'success'=>'成功'
    ]);
    load('show.php');
}else{
    if(isset($_SESSION['user'])){
        ins('logs',[
            'user_id'=>$_SESSION['user']['id'],
            'action'=>'登出 ',
            'success'=>'成功'
        ]);
    }
    session_destroy();
    load('index.php');
}