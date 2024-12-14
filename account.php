<?php
    require_once('db.php');
    if(!isset($_SESSION['user'])){
        alert('未登入','index.php');
    }

    if(!$_SESSION['user']['permission']){
        alert('權限不足','show.php');
    }

    $_SESSION['time'] = intval($_GET['time'] ?? $_SESSION['time'] ?? 60);
    $users = sels('users');
    $logs = sels('logs',1,"ORDER BY `date` DESC");

    if(isset($_POST['ins'])){
        $user = sel('users',['account'=>$_POST['account']]);
        if(!empty($user)){
            alert('帳號已存在');
        }
        $user_id = ins('users',array_filter($_POST));
        upd('users',['number'=>str_pad($user_id-1,4,'0',STR_PAD_LEFT)],['id'=>$user_id]);
        load();
    }

    if(isset($_POST['upd'])){
        if($_SESSION['user']['id'] == $_POST['upd']){
            alert('無法修改正在使用的帳號');
        }
        $user = sel('users',['account'=>$_POST['account']]);
        if(!empty($user) && $_POST['upd'] != $user['id']){
            alert('帳號已存在');
        }
        $data = $_POST;
        unset($data['upd']);
        upd('users',$data,['id'=>$_POST['upd']]);
        load();
    }

    if(isset($_POST['del'])){
        if($_SESSION['user']['id'] == $_POST['del']){
            alert('無法修改正在使用的帳號');
        }
        del('users',['id'=>$_POST['del']]);
        load();
    }

    if(isset($_GET['search'])){
        $users = sels('users',[
            'number'=>'%'.$_GET['search'].'%',
            'account'=>'%'.$_GET['search'].'%',
            'name'=>'%'.$_GET['search'].'%'
        ],"ORDER BY `".$_GET['order']."` ".$_GET['by'],true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('header.php')?>
</head>
<body>
    <?php require_once('nav.php')?>
    <div class="container bg-light p-4 my-5 rounded shadow">
        <div class="float-right">
            <form action="" class="form-inline">
                <label for="">剩餘時間： <span class="time"><?=$_SESSION['time']?></span>s</label>
                <input type="text" class="form-control m-2" name="time" placeholder="關鍵字" value="<?=$_SESSION['time']?>">
                <button class="btn btn-outline-secondary">修改時間</button>
                <a href="" class="btn btn-outline-secondary ml-2">重新計時</a>
            </form>
        </div>
        <h2 class="mb-5">會員管理</h2>
        <div class="float-right">
            <form action="" class="form-inline">
                <input type="text" class="form-control" name="search" placeholder="關鍵字">
                <label for="" class="mx-2">排序方式</label>
                <select name="order" class="form-control">
                    <option value="number">使用者編號</option>
                    <option value="account">帳號</option>
                    <option value="name">姓名</option>
                </select>
                <select name="by" class="form-control mx-2">
                    <option value="ASC">升冪</option>
                    <option value="DESC">降冪</option>
                </select>
                <button class="btn btn-secondary">查尋</button>
            </form>
        </div>
        <a href="#ins" data-toggle="modal" class="btn btn-warning">新增使用者</a>
        <a href="#log" data-toggle="modal" class="btn btn-outline-secondary">登入登出紀錄</a>
        <table class="table table-striped table-borderless mt-4">
            <tr>
                <th class="col-2 text-center">使用者編號</th>
                <th class="col-2 text-center">帳號</th>
                <th class="col-2 text-center">姓名</th>
                <th class="col-2 text-center">密碼</th>
                <th class="col-2 text-center">權限</th>
                <th class="col-2 text-center">操作</th>
            </tr>
            <?php foreach ($users as $user) {?>
                <form action="" method="post">
                    <tr>
                        <td class="text-center"><?=$user['number']?></td>
                        <td class="text-center">
                            <input type="text" class="form-control" name="account" required value="<?=$user['account']?>" <?=$user['id'] == 1 ? 'disabled' : ''?>>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control" name="name" required value="<?=$user['name']?>" <?=$user['id'] == 1 ? 'disabled' : ''?>>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control" name="password" required value="<?=$user['password']?>" <?=$user['id'] == 1 ? 'disabled' : ''?>>
                        </td>
                        <td class="text-center">
                            <select name="permission" class="form-control" <?=$user['id'] == 1 ? 'disabled' : ''?>>
                                <option value="0" <?=$user['permission'] == 0 ? 'selected' : ''?>>一般使用者</option>
                                <option value="1" <?=$user['permission'] == 1 ? 'selected' : ''?>>管理者</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-secondary" value="<?=$user['id']?>" name="upd">修改</button>
                            <button class="btn btn-outline-danger" value="<?=$user['id']?>" name="del">刪除</button>
                        </td>
                    </tr>
                </form>
            <?php }?>
        </table>
    </div>
    <div id="ins" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h2>新增使用者</h2>
                    </div>
                    <div class="modal-body">
                        <label for="" class="mt-2">帳號</label>
                        <input type="text" class="form-control" required name="account">
                        <label for="" class="mt-2">姓名</label>
                        <input type="text" class="form-control" required name="name">
                        <label for="" class="mt-2">密碼</label>
                        <input type="text" class="form-control" required name="password">
                        <label for="" class="mt-2">權限</label>
                        <select name="permission" class="form-control">
                            <option value="0">一般使用者</option>
                            <option value="1">管理者</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" name="ins">新增</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="log" class="modal fade">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>登入登出紀錄</h2>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-borderless rounded">
                        <tr>
                            <th class="text-center col-2">使用者</th>
                            <th class="text-center col-6">時間</th>
                            <th class="text-center col-2">動作</th>
                            <th class="text-center col-2">狀態</th>
                        </tr>
                        <?php foreach($logs as $log){
                            $user = sel('users',['id'=>$log['user_id']]);    
                        ?>
                            <tr>
                                <td class="text-center"><?=$user['account']?></td>
                                <td class="text-center"><?=$log['date']?></td>
                                <td class="text-center"><?=$log['action']?></td>
                                <td class="text-center"><?=$log['success']?></td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="time" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>是否繼續使用?</h2>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <a href="" class="btn btn-secondary">Yes</a>
                        <a href="log.php" class="btn btn-outline-secondary">否</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let time = <?=$_SESSION['time']?>;
        setInterval(() => {
            time--
            if(time <=0){
                setTimeout(() => {
                    location.href = 'log.php'
                }, 5000);
                $('#time').modal()
            }else{
                $('.time').text(time)
            }
        }, 1000);
    </script>
</body>
</html>