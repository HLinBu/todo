<?php
require_once("db.php");
$works = sels("work", ["user_id" => $_SESSION["user"]["id"]]);
if (isset($_GET["select"])) {
    $query = [];
    $query[] = "`user_id` = '{$_SESSION['user']['id']}'";
    if (!empty($_GET["name"])) {
        $query[] = "`name` LIKE '%{$_GET["name"]}%'";
    }
    if (!empty($_GET["date"])) {
        $query[] = "`date` = '{$_GET['date']}'";
    }
    if (!empty($_GET["start_time"]) || $_GET["start_time"] != "") {
        if (!empty($_GET["end_time"])) {
            $where = ">=";
        } else {
            $where = "=";
        }
        $query[] = "`start_time` $where '{$_GET['start_time']}'";
    }
    if (!empty($_GET["end_time"])) {
        if (isset($_GET["start_time"]) || $_GET["start_time"] != "") {
            $where = ">=";
        } else {
            $where = "=";
        }
        $query[] = "`end_time` $where '{$_GET['end_time']}'";
    }
    if (!empty($_GET["mode"]) || $_GET["mode"] != "") {
        $query[] = "`mode` = '{$_GET['mode']}'";
    }
    if (!empty($_GET["order"]) || $_GET["order"] != "") {
        $query[] = "`order` = '{$_GET['order']}'";
    }
    $_SESSION["work"] = sels("work", [], join(" AND ", $query));
    $_SESSION["get"]=$_SERVER["QUERY_STRING"];
}

if (isset($_POST["update"]) || isset($_POST["insert"])) {
    if ($_POST["end_time"] > $_POST["start_time"]) {
        if (isset($_POST["update"])) {
            del("work", ["id" => $_POST["update"]]);
        }
        ins("work", [
            "name" => $_POST["name"],
            "date" => $_POST["date"],
            "start_time" => $_POST["start_time"],
            "end_time" => $_POST["end_time"],
            "mode" => $_POST["mode"],
            "order" => $_POST["order"],
            "des" => $_POST["des"],
            "user_id" => $_SESSION["user"]["id"],
        ]);
    }
    header("location:work.php?".$_SESSION["get"]);
}

if (isset($_POST["delete"])) {
    del("work", ["id" => $_POST["delete"]]);
    header("location:work.php?".$_SESSION["get"]);
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
        <h2 class="header">TODO 工作表</h2>
        <hr>
        <a class="btn" href="work_table.php">切換模式</a>
        <a data-toggle="modal" href="#ins" class="btn">新增工作</a>

        <form class="pull-right" action="file_in.php" method="post" enctype="multipart/form-data">
            <div style="display: none;">
                <input type="file" name="file" hidden>
            </div>
            <button class="btn" name="in" type="button">匯入</button>
            <a class="btn" href="file_out.php?id=<?= $_SESSION["user"]["id"] ?>">匯出</a>
        </form><br><br>

        <form action="" method="get">
            名稱<input value="<?= (isset($_GET["name"])) ? $_GET["name"] : "" ?>" name="name" type="text">
            日期<input value="<?= (isset($_GET["date"])) ? $_GET["date"] : "" ?>" name="date" type="date">
            時間<select class="input-small" name="start_time">
                <option value="">請選擇</option>
                <?php for ($i = 0; $i < 24; $i++) { ?>
                    <option <?= (isset($_GET["start_time"]) && $_GET["start_time"] == $i) ? "selected" : "" ?> value="<?= $i ?>"><?= $i ?>時</option>
                <?php } ?>
            </select>&ensp;至
            <select class="input-small" name="end_time">
                <option value="">請選擇</option>
                <?php for ($i = 1; $i <= 24; $i++) { ?>
                    <option <?= (isset($_GET["end_time"]) && $_GET["end_time"] == $i) ? "selected" : "" ?> value="<?= $i ?>"><?= $i ?>時</option>
                <?php } ?>
            </select><br>

            處理<select name="mode">
                <option value="">請選擇</option>
                <?php for ($i = 0; $i < 3; $i++) { ?>
                    <option <?= (isset($_GET["mode"]) && $_GET["mode"] == $i) ? "selected" : "" ?> value="<?= $i ?>"><?= $mode[$i] ?></option>
                <?php } ?>
            </select>
            優先<select name="order">
                <option value="">請選擇</option>
                <?php for ($i = 0; $i < 3; $i++) { ?>
                    <option <?= (isset($_GET["order"]) && $_GET["order"] == $i) ? "selected" : "" ?> value="<?= $i ?>"><?= $order[$i] ?></option>
                <?php } ?>
            </select>
            <button class="btn" name="select" type="submit">篩選</button>
        </form>

        <div style="padding:16px" class="card">
            <table class="table">
                <h3>
                    <?= (isset($_SESSION["work"])) ? "您所篩選的工作表<a href='work_hidden.php' class='btn'>返回</a>" : "今日工作表(" . date("Y-m-d") . ")" ?>
                </h3>
                <thead>
                    <th>時間</th>
                    <th>工作計畫</th>
                </thead>
                <tbody class="title">
                    <?php for ($i = 0; $i < 24; $i += 2) { ?>
                        <tr style="height:40px">
                            <td><?= str_pad($i, 2, '0', STR_PAD_LEFT) . "-" . str_pad(($i + 2), 2, '0', STR_PAD_LEFT) ?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
            <div class="data"></div>
        </div>

        <!-- 新增 -->
        <form action="" method="post">
            <div class="modal fade hide" id="ins">
                <div class="modal-header">
                    <h2>工作編輯</h2>
                </div>

                <div class="modal-body">
                    工作名稱<input name="name" type="text" required><br>
                    工作日期<input name="date" type="date" required><br>
                    開始時間<select name="start_time">
                        <?php for ($i = 0; $i < 24; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php } ?>
                    </select><br>
                    結束時間<select name="end_time">
                        <?php for ($i = 1; $i <= 24; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php } ?>
                    </select><br>
                    處理狀態<select name="mode">
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                            <option value="<?= $i ?>"><?= $mode[$i] ?></option>
                        <?php } ?>
                    </select><br>
                    優先順序<select name="order">
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                            <option value="<?= $i ?>"><?= $order[$i] ?></option>
                        <?php } ?>
                    </select><br>
                    工作說明<textarea name="des" cols="30" rows="3" required></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-inverse" name="insert" type="submit">新增工作</button>
                </div>

            </div>
        </form>

        <!-- 修改 -->
        <?php foreach ($works as $work) { ?>
            <form action="" method="post">
                <div class="modal fade hide" id="work<?= $work["id"] ?>">
                    <div class="modal-header">
                        <h2>工作編輯
                            <button onclick="return confirm('確定要刪除此工作嗎?')" class="btn btn-danger pull-right" value="<?= $work["id"] ?>" name="delete" type="submit">刪除</button>
                        </h2>
                    </div>
                    <div class="modal-body">
                        工作名稱<input value="<?= $work["name"] ?>" name="name" type="text" required><br>
                        工作日期<input value="<?= $work["date"] ?>" name="date" type="date" required><br>
                        開始時間<select name="start_time">
                            <?php for ($i = 0; $i < 24; $i++) { ?>
                                <option <?= $work["start_time"] == $i ? "selected" : "" ?> value="<?= $i ?>"><?= $i ?></option>
                            <?php } ?>
                        </select><br>
                        結束時間<select name="end_time">
                            <?php for ($i = 1; $i <= 24; $i++) { ?>
                                <option <?= $work["end_time"] == $i ? "selected" : "" ?> value="<?= $i ?>"><?= $i ?></option>
                            <?php } ?>
                        </select><br>
                        處理狀態<select name="mode">
                            <?php for ($i = 0; $i < 3; $i++) { ?>
                                <option <?= $work["mode"] == $i ? "selected" : "" ?> value="<?= $i ?>"><?= $mode[$i] ?></option>
                            <?php } ?>
                        </select><br>
                        優先順序<select name="order">
                            <?php for ($i = 0; $i < 3; $i++) { ?>
                                <option <?= $work["order"] == $i ? "selected" : "" ?> value="<?= $i ?>"><?= $order[$i] ?></option>
                            <?php } ?>
                        </select><br>
                        工作說明<textarea name="des" cols="30" rows="3" required><?= $work["des"] ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-inverse" value="<?= $work["id"] ?>" name="update" type="submit">修改</button>
                    </div>
                </div>
            </form>
        <?php } ?>

    </div>
    <script src="js/app.js"></script>
</body>

</html>