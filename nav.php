<nav class="navbar navbar-expand-sm navbar-dark coffee">
    <div class="container">
        <a href="show.php" class="navbar-brand">咖啡商品展示系統</a>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="sell.php?type=style" class="nav-link">上架商品</a>
                </li>
                <?php if($_SESSION['user']['permission']){?>
                    <li class="nav-item">
                        <a href="account.php" class="nav-link">會員管理</a>
                    </li>
                <?php }?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="log.php" class="btn btn-outline-light">登出</a>
                </li>
            </ul>
        </div>
    </div>
</nav>