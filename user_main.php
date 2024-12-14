<?php
require_once("db.php");
$users = sels("users", "1", "ORDER BY `level` DESC");
$records = sels("record");
if (isset($_POST["update"])) {
    if ($_SESSION["user"]["id"] != $_POST["update"]) {
        upd("users", [
            "account" => $_POST["acc"],
            "password" => $_POST["pas"],
            "level" => $_POST["level"],
        ], [
            "id" => $_POST["update"]
        ]);
        header("location:user_main.php");
    } else {
        upd("users", [
            "account" => $_POST["acc"],
            "password" => $_POST["pas"],
        ], [
            "id" => $_POST["update"]
        ]);
        echo "<script>alert('此會員使用中 不可修改權限');location.href=''</script>";
    }
}

if (isset($_POST["insert"])) {
    $user = sel("users", ["account" => $_POST["acc"]]);
    if (empty($user)) {
        ins("users", [
            "account" => $_POST["acc"],
            "password" => $_POST["pas"],
            "level" => $_POST["level"]
        ]);
        header("location:user_main.php");
    } else {
        echo "<script>alert('此帳號已被使用');location.href=''</script>";
    }
}
if (isset($_POST["delete"])) {
    if ($_SESSION["user"]["id"] != $_POST["delete"]) {
        del("users", ["id" => $_POST["delete"]]);
        header("location:user_main.php");
    } else {
        echo "<script>alert('此會員使用中 不可刪除');location.href=''</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("header.php"); ?>
</head>

<body>
    <?php require_once("nav.php"); ?>
    <div class="container">
        <h2 class="header">會員管理</h2>
        <hr>
        <a class="btn" data-toggle="modal" href="#insert">新增會員</a>
        <a class="btn" data-toggle="modal" href="#record">查看紀錄</a>
        <br><br>
        <div class="card">
            <table class="table">
                <thead>
                    <th>會員帳號</th>
                    <th>會員密碼</th>
                    <th>會員權限</th>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <form action="" method="post">
                            <tr>
                                <td>
                                    <input name="acc" value="<?= $user["account"] ?>" placeholder="帳號" type="text" required>
                                </td>
                                <td>
                                    <input name="pas" value="<?= $user["password"] ?>" placeholder="密碼" type="password" required>
                                </td>
                                <td>
                                    <select name="level">
                                        <option <?= $user["level"] == 0 ? "selected" : "" ?> value="0">一般會員</option>
                                        <option <?= $user["level"] == 1 ? "selected" : "" ?> value="1">管理員</option>
                                    </select>
                                </td>
                                <td><button name="update" value="<?= $user["id"] ?>" class="btn" type="submit">修改</button></td>
                                <td><button name="delete" value="<?= $user["id"] ?>" class="btn btn-danger" type="submit">刪除</button></td>
                            </tr>
                        </form>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <form action="" method="post">
            <div class="modal fade hide" id="insert">
                <div class="modal-header">
                    <h2>新增會員</h2>
                </div>
                <div class="modal-body">
                    <input name="acc" placeholder="帳號" type="text" required><br>
                    <input name="pas" placeholder="密碼" type="text" required><br>
                    <select name="level">
                        <option value="0">一般會員</option>
                        <option value="1">管理員</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-inverse" name="insert" type="submit">新增會員</button>
                </div>
            </div>
        </form>

        <div class="modal fade hide" id="record">
            <div class="modal-header">
                <h2>登入登出紀錄</h2>
            </div>

            <div class="modal-body">
                <table class="table">
                    <thead>
                        <th>會員帳號</th>
                        <th>時間</th>
                        <th>模式</th>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record) {
                            $user = sel("users", ["id" => $record["user_id"]]);
                        ?>
                            <tr>
                                <td><?= $user["account"] ?></td>
                                <td><?= $record["date"] ?></td>
                                <td><?= $record["mode"] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>
</body>

</html>