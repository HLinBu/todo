<?php
    require_once('db.php');
    if(!isset($_SESSION['user'])){
        alert('未登入','index.php');
    }

    $styles = sels('styles');
    if(!isset($_SESSION['sell'])){
        $_SESSION['sell'] = [
            'template'=>$styles[0]['template'],
            'style_id'=>$styles[0]['id'],
            'img'=>'',
            'img_name'=>'',
            'title'=>'',
            'content'=>'',
            'date'=>'',
            'cost'=>'',
            'link'=>'',
        ];
    }
    if(isset($_GET['change'])){
        $_SESSION['sell']['template'] = $styles[$_GET['change']]['template'];
        $_SESSION['sell']['style_id'] = $styles[$_GET['change']]['id'];
    }

    if(isset($_GET['addStyle'])){
        ins('styles',['template'=>$_GET['addStyle']]);
        load('?type=style');
    }

    if(!isset($_GET['type'])){
        load('?type=style');
    }

    if(isset($_POST['info'])){
        $_SESSION['sell'] = array_merge($_SESSION['sell'],array_filter($_POST));
        if($_FILES['file']['size'] > 0){
            $img = './img/'.time().$_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'],$img);
            $_SESSION['sell']['img'] = $img;
            $_SESSION['sell']['img_name'] = $_FILES['file']['name'];
        }
        $_SESSION['sell']['date'] = date("Y-m-d h:i:s");
        load('?type=preview');
    }
    if(isset($_POST['submit'])){
        foreach($_SESSION['sell'] as $c){
            if(empty($c)){
                alert('請填寫完整資料','?type=info');
            }
        }
        unset($_SESSION['sell']['template']);
        unset($_SESSION['sell']['date']);
        ins('sells',$_SESSION['sell']);
        unset($_SESSION['sell']);
        load('show.php');
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
        <h2 class="mb-5">上架商品</h2>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="?type=style" class="nav-link text-secondary <?=$_GET['type'] == 'style' ? 'active' : ''?>">選擇版型</a>
            </li>
            <li class="nav-item">
                <a href="?type=info" class="nav-link text-secondary <?=$_GET['type'] == 'info' ? 'active' : ''?>">填寫資料</a>
            </li>
            <li class="nav-item">
                <a href="?type=preview" class="nav-link text-secondary <?=$_GET['type'] == 'preview' ? 'active' : ''?>">預覽</a>
            </li>
            <li class="nav-item">
                <a href="?type=submit" class="nav-link text-secondary <?=$_GET['type'] == 'submit' ? 'active' : ''?>">確定送出</a>
            </li>
        </ul>
        <div class="bg-white pt-3 p-2 border-left border-right border-bottom">
            <?php if($_GET['type'] == 'style'){?>
                <a href="#new" data-toggle="modal" class="btn btn-warning">新增版型</a>

                <div class="row">
                    <?php foreach ($styles as $k => $style) {?>
                        <div class="col-6 my-3">
                            <div id="<?=$k?>" class="grid cur <?=$style['id'] == $_SESSION['sell']['style_id'] ? 'selected' : ''?>" style="grid-template-areas: <?=$style['template']?>">
                                <div class="img">圖片</div>
                                <div class="title">商品名稱</div>
                                <div class="content">商品簡介</div>
                                <div class="date">發佈日期</div>
                                <div class="cost">費用：0000</div>
                                <div class="link">相關連結</div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            <?php }?>
            <?php if($_GET['type'] == 'info'){?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <label for="" class="mt-2">商品名稱</label>
                            <input type="text" class="form-control" name="title" required value="<?=$_SESSION['sell']['title']?>">
                            <label for="" class="mt-2">商品簡介</label>
                            <input type="text" class="form-control" name="content" required value="<?=$_SESSION['sell']['content']?>">
                            <label for="" class="mt-2">費用</label>
                            <input type="number" min="1" class="form-control" name="cost" required value="<?=$_SESSION['sell']['cost']?>">
                            <label for="" class="mt-2">相關連結</label>
                            <input type="text" class="form-control" name="link" required value="<?=$_SESSION['sell']['link']?>">
                        </div>
                        <div class="col">
                            <label for="" class="mt-2">圖片</label>
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" class="form-control imgName" readonly value="<?=$_SESSION['sell']['img_name']?>" placeholder="未選擇圖片">
                                </div>
                                <div class="col-4">
                                    <label for="file" class="w-100 btn btn-outline-secondary">選擇圖片</label>
                                    <input type="file" name="file" id="file" hidden accept="image/*">
                                </div>
                            </div>
                            <img <?=$_SESSION['sell']['img'] != '' ? 'src="'.$_SESSION['sell']['img'].'"' :''?> class="preview">
                        </div>
                    </div>
                    <div class="text-center my-3">
                        <button class="btn btn-secondary" name="info">送出資料</button>
                    </div>
                </form>
            <?php }?>
            <?php if($_GET['type'] == 'preview'){?>
                <div class="col-6 offset-3">
                    <div class="grid" style="grid-template-areas: <?=$_SESSION['sell']['template']?>">
                        <div class="img">
                            <?= empty($_SESSION['sell']['img']) ? '圖片' : '<img src="'.$_SESSION['sell']['img'].'">'?>
                        </div>
                        <div class="title"><?=$_SESSION['sell']['title'] ?: '商品名稱'?></div>
                        <div class="content"><?=$_SESSION['sell']['content'] ?: '商品簡介'?></div>
                        <div class="date"><?=$_SESSION['sell']['date'] ?: '發佈日期'?></div>
                        <div class="cost">費用：<?=$_SESSION['sell']['cost'] ?: '0000'?></div>
                        <div class="link">
                            <a href="<?=$_SESSION['sell']['link']?>" class="text-white">相關連結</a>
                        </div>
                    </div>
                    <div class="text-center my-3">
                        <a href="?type=submit" class="btn btn-secondary">下一步</a>
                    </div>
                </div>
            <?php }?>
            <?php if($_GET['type'] == 'submit'){?>
                <div class="text-center">
                    <h4>請按下送出按鈕來做最後確認</h4>
                    <form action="" method="post" class="my-3">
                        <button class="btn btn-secondary" name="submit" onclick="return confirm('是否上架該商品?')">送出</button>
                    </form>
                </div>
            <?php }?>
        </div>
    </div>
    <div id="new" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>新增版型</h2>
                </div>
                <div class="modal-body">
                    <div class="col-6 offset-3">
                        <div class="grid template">
                            <div class="t-img img">圖片</div>
                            <div class="t-title title">商品名稱</div>
                            <div class="t-content content">商品簡介</div>
                            <div class="t-date date">發佈日期</div>
                            <div class="t-cost cost">費用：0000</div>
                            <div class="t-link link">相關連結</div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-secondary add" onclick="addElement(event,'img',4)">圖片</button>
                        <button class="btn btn-outline-secondary add" onclick="addElement(event,'title',1)">商品名稱</button>
                        <button class="btn btn-outline-secondary add" onclick="addElement(event,'content',2)">商品簡介</button>
                        <button class="btn btn-outline-secondary add" onclick="addElement(event,'date',1)">發佈日期</button>
                        <button class="btn btn-outline-secondary add" onclick="addElement(event,'cost',1)">費用</button>
                        <button class="btn btn-outline-secondary add" onclick="addElement(event,'link',1)">相關連結</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="resetStyle()">重設</button>
                    <button class="btn btn-secondary" onclick="addStyle()">新增</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#file').change(function(e){
            let file = e.target.files[0]
            $('.imgName').val(file.name)
            $('.preview').attr('src',URL.createObjectURL(file))
        })
        var template = [[],[],[],[],[]]
        resetStyle()
        function addElement(e,name,rows){
            for (let c = 0; c < 2; c++) {
                for (let r = 0; r < 5; r++) {
                    if(!template[r][c] && ((rows+r-1) < 5)){
                        for (let i = 0; i < rows; i++) {
                            template[r+i][c] = name
                        }
                        $('.t-'+name)
                        .attr('style',`grid-area: ${r+1} / ${c+1} / span ${rows} / span 1`)
                        .show()
                        $(event.target).hide()
                        return
                    }
                }
            }
            alert('空間不足')
            resetStyle()
        }
        function addStyle(){
            for (let c = 0; c < 2; c++) {
                for (let r = 0; r < 5; r++) {
                    if(!template[r][c]){
                        alert('請將所有元素填入')
                        return
                    }
                }
            }
            let array = [];
            template.forEach(function(e){
                array.push(e.join(' '))
            })
            location.href = `?addStyle='${array.join("''")}'`
        }
        function resetStyle(){
            template = [[],[],[],[],[]]
            $('.template div').hide()
            $('.add').show()
        }
        $('.cur').click(function(){
            fetch('?change='+this.id)
            $('.selected').removeClass('selected')
            $(this).addClass('selected')
        })
    </script>
</body>
</html>