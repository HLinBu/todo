<?php
if (empty($_SESSION["user"])) {
    echo "<script>alert('未登入');location.href='index.php'</script>";
}
?>
<div class="navbar-static-top navbar">
    <div class="navbar-inner">
        <div class="container">
            <a href="" class="brand"><img src="logo.png" style="height: 20px;"></a>
            <ul class="nav">
                <li><a href="work_hidden.php">TODO 工作表</a></li>
                <li><a href="chart.php">統計圖</a></li>
                <?php if ($_SESSION["user"]["level"] == 1) { ?>
                    <li><a href="user_main.php">會員網站後台管理</a></li>
                <?php } ?>
            </ul>
            <ul class="pull-right nav">
                <li><a href="logout.php">登出</a></li>
            </ul>
        </div>
    </div>
</div><br>