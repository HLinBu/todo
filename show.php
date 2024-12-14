<?php
    require_once('db.php');
    if(!isset($_SESSION['user'])){
        alert('未登入','index.php');
    }

    $sells = sels('sells',1,"ORDER BY `date` DESC");

    if(isset($_POST['upd'])){
        $data = $_POST;
        unset($data['upd']);
        if($_FILES['file']['size'] > 0){
            $img = './img/'.time().$_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'],$img);
            $data['img'] = $img;
            $data['img_name'] = $_FILES['file']['name'];
        }
        upd('sells',$data,['id'=>$_POST['upd']]);
        load();
    }
    if(isset($_GET['search'])){
        $other = '';
        if(!empty($_GET['min'])){
            $other .= " AND `cost` >= ".$_GET['min'];
        }
        if(!empty($_GET['max'])){
            $other .= " AND `cost` <= ".$_GET['max'];
        }
        $other .= " ORDER BY `date` DESC";
        $sells = sels('sells',[
            'title'=>'%'.$_GET['search'].'%',
            'content'=>'%'.$_GET['search'].'%',
        ],$other,true);
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
        <h2 class="mb-5">商品展示區</h2>
        <div class="d-flex justify-content-end">
            <form action="" class="form-inline">
                <input type="text" class="form-control" placeholder="關鍵字" name="search">
                <input type="number" min="1" name="min" class="mx-2 form-control" placeholder="最低價位">
                <label for="">~</label>
                <input type="number" min="1" name="max" class="mx-2 form-control" placeholder="最高價位">
                <button class="btn btn-secondary">查尋</button>
            </form>
        </div>
        <div class="row">
            <?php foreach ($sells as $sell) {
                $style = sel('styles',['id'=>$sell['style_id']]);    
            ?>
                <div class="col-6 my-3">
                    <div class="grid" style="grid-template-areas: <?=$style['template']?>">
                        <div class="img">
                            <?= empty($sell['img']) ? '圖片' : '<img src="'.$sell['img'].'">'?>
                        </div>
                        <div class="title"><?=$sell['title'] ?: '商品名稱'?></div>
                        <div class="content"><?=$sell['content'] ?: '商品簡介'?></div>
                        <div class="date"><?=$sell['date'] ?: '發佈日期'?></div>
                        <div class="cost">費用：<?=$sell['cost'] ?: '0000'?></div>
                        <div class="link">
                            <a href="<?=$sell['link']?>" class="text-white">相關連結</a>
                        </div>
                    </div>
                    <?php if($_SESSION['user']['permission']){?>
                        <div class="text-center mt-3">
                            <a href="#edit<?=$sell['id']?>" data-toggle="modal" class="btn btn-secondary">編輯商品</a>
                        </div>
                        <div id="edit<?=$sell['id']?>" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h2>編輯商品</h2>
                                        </div>
                                        <div class="modal-body">
                                            <label for="" class="mt-2">圖片</label>
                                            <div class="row">
                                                <div class="col-8">
                                                    <input type="text" class="form-control imgName<?=$sell['id']?>" readonly value="<?=$sell['img_name']?>" placeholder="未選擇圖片">
                                                </div>
                                                <div class="col-4">
                                                    <label for="file<?=$sell['id']?>" class="w-100 btn btn-outline-secondary">選擇圖片</label>
                                                    <input type="file" name="file" id="file<?=$sell['id']?>" data-id="<?=$sell['id']?>" hidden accept="image/*">
                                                </div>
                                            </div>
                                            <label for="" class="mt-2">商品名稱</label>
                                            <input type="text" class="form-control" name="title" required value="<?=$sell['title']?>">
                                            <label for="" class="mt-2">商品簡介</label>
                                            <input type="text" class="form-control" name="content" required value="<?=$sell['content']?>">
                                            <label for="" class="mt-2">費用</label>
                                            <input type="number" min="1" class="form-control" name="cost" required value="<?=$sell['cost']?>">
                                            <label for="" class="mt-2">相關連結</label>
                                            <input type="text" class="form-control" name="link" required value="<?=$sell['link']?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" name="upd" value="<?=$sell['id']?>">修改</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>   
            <?php } ?>
        </div>
    </div>
    <script>
        $(`[name="file"]`).change(function(e){
            let file = e.target.files[0]
            let id = $(this).data('id')
            $('.imgName'+id).val(file.name)
        })
    </script>
</body>
</html>