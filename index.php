<?php
require_once("db.php");

if (!isset($_SESSION["error"])) {
    $_SESSION["error"] = 0;
}

if ($_SESSION["error"] >= 3) {
    $_SESSION["error"] = 0;
    echo "<script>alert('錯誤頁面');location.href='error.php'</script>";
}

if (isset($_POST["login"])) {
    $user = sel("users", ["account" => $_POST["acc"], "password" => $_POST["pas"]]);
    //  && $_POST["captcha"] == $_SESSION["answer"]
    if (!empty($user)) {
        $_SESSION["error"] = 0;
        $_SESSION["tmp_user"] = $user;
        header("location:work_hidden.php");
        // echo "<script>alert('二次驗證');location.href='captcha.php?error=0'</script>";
    } else {
        $_SESSION["error"] += 1;
        $error = [];
        if (empty(sel("users", ["account" => $_POST["acc"]]))) {
            $error[] = "帳號有誤";
            if (empty(sel("users", ["password" => $_POST["pas"]]))) {
                $error[] = "密碼有誤";
            }
        } else {
            if (empty($user)) {
                $error[] = "密碼有誤";
            }
        }
        // if ($_SESSION["answer"] != $_POST["captcha"]) {
        //     $error[] = "驗證碼有誤";
        // }
        echo "<script>alert('" . join(" ", $error) . "');location.href=''</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("header.php") ?>
</head>

<body>
    <form action="" method="post">
        <div class="login">
            <div class="card span6">
                <img src="logo.png" style="height: 40px;"><br><br>
                <h1>TODO 工作管理系統</h1>
                <hr>
                <input name="acc" placeholder="帳號" type="text" required><br>
                <input name="pas" placeholder="密碼" type="password" required><br>
                <!-- <div class="num"></div><br>
                <input id="place" name="captcha" placeholder="驗證碼" type="text" required><br>
                <button class="btn" id="renew" type="button">重新產生</button> -->
                <button class="btn btn-inverse" name="login" type="submit">登入</button>
            </div>
        </div>
    </form>
</body>
<script>
    // function updaterand() {
    //     fetch("rand.php")
    //         .then(res => res.json())
    //         .then(res => {
    //             res.forEach(e => {
    //                 let img = getimg(e);
    //                 $(img).attr("id", e);
    //                 $(".num").append(img);
    //                 $(img).draggable({
    //                     revert: true,
    //                     helper: "clone"
    //                 })
    //             })
    //         })
    // }

    // function getimg(e) {
    //     let img = new Image()
    //     img.src = "svg.php?index=" + e
    //     return img
    // }

    // $(function() {
    //     $("#renew").click(function() {
    //         updaterand();
    //         $(".num").empty()
    //     })
    //     updaterand();
    //     $("#place").droppable({
    //         drop: function(e, u) {
    //             $(this).val($(this).val() + u.draggable[0].id)
    //         }
    //     })
    // })
</script>

</html>