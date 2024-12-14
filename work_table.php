<?php
require_once("db.php");
$ex = "";
if (isset($_GET["change"])) {
    $ex = "ORDER BY `{$_GET['choose']}` {$_GET['order']}";
}
if (isset($_POST["delete"])) {
    del("work", ["id" => $_POST["delete"]]);
    header("location:work_table.php");
}
$works = sels("work", ["user_id" => $_SESSION["user"]["id"]], $ex);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("header.php"); ?>
</head>

<body>
    <?php require_once("nav.php"); ?>
    <div class="container">
        <h2 class="header">TODO 工作表</h2>
        <hr>
        <a class="btn" href="work_hidden.php">切換模式</a>
        <form class="pull-right" action="" method="get">
            <select name="choose">
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "name") ? "selected" : "" ?> value="name">工作名稱</option>
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "date") ? "selected" : "" ?> value="date">工作日期</option>
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "start_time") ? "selected" : "" ?> value="start_time">開始時間</option>
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "end_time") ? "selected" : "" ?> value="end_time">結束時間</option>
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "mode") ? "selected" : "" ?> value="mode">處理狀態</option>
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "order") ? "selected" : "" ?> value="order">優先順序</option>
                <option <?= (isset($_GET["choose"]) && $_GET["choose"] == "des") ? "selected" : "" ?> value="des">工作說明</option>
            </select>
            <select name="order">
                <option <?= (isset($_GET["order"]) && $_GET["order"] == "ASC") ? "selected" : "" ?> value="ASC">遞增</option>
                <option <?= (isset($_GET["order"]) && $_GET["order"] == "DESC") ? "selected" : "" ?> value="DESC">遞減</option>
            </select>
            <button name="change" class="btn" type="submit">排序</button>
        </form><br><br>

        <div class="card">
            <table class="table">
                <thead>
                    <th>工作名稱</th>
                    <th>工作日期</th>
                    <th>開始時間</th>
                    <th>結束時間</th>
                    <th>處理狀態</th>
                    <th>優先順序</th>
                    <th>工作說明</th>
                    <th>刪除</th>
                </thead>
                <tbody>
                    <?php foreach ($works as $work) { ?>
                        <form action="" method="post">
                            <tr>
                                <td><?= $work["name"] ?></td>
                                <td><?= $work["date"] ?></td>
                                <td><?= $work["start_time"] ?></td>
                                <td><?= $work["end_time"] ?></td>
                                <td><?= $mode[$work["mode"]] ?></td>
                                <td><?= $order[$work["order"]] ?></td>
                                <td><?= $work["des"] ?></td>
                                <td><button name="delete" class="btn btn-danger" value="<?= $work["id"] ?>" onclick="return confirm('確定要刪除此工作嗎?')" type="submit">刪除</button></td>
                            </tr>
                        </form>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>