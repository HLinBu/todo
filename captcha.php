<?php
require_once("db.php");
if (empty($_SESSION["tmp_user"])) {
    echo "<script>alert('未登入');location.href='index.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("header.php") ?>
</head>

<body>
    <div class="login">
        <div class="card">
            <h2>二次驗證(九宮格)</h2>
            <div class="box-wrap">
                <?php for ($i = 1; $i <= 9; $i++) { ?>
                    <button type="button" id="<?= $i ?>" class="box"><?= $i ?></button>
                <?php } ?>
            </div>
            <div>
                <button id="resetUI" class="btn pull-left" type="button">重新選取</button>
            </div>
        </div>
    </div>
    <script>
        var error = new URL(location.href).searchParams.get("error")

        function Compare(num) {
            switch (num) {
                case 123:
                    return 1
                    break;
                case 456:
                    return 1
                    break;
                case 789:
                    return 1
                    break;
                case 147:
                    return 1
                    break;
                case 258:
                    return 1
                    break;
                case 369:
                    return 1
                    break;
                case 159:
                    return 1
                    break;
                case 357:
                    return 1
                    break;
                default:
                    return 0
                    break;
            }
        }
        var num = []

        function updateUI() {
            $(document).on(`click`, `.box`, function() {
                $(this).css({
                    "background-color": "gray"
                })
                if ((num.length) < 3) {
                    num[num.length] = $(this)[0].id;
                }
                if ((num.length) == 3) {
                    let sortnum = Compare(parseInt((num.sort()).join("")))
                    if (sortnum == 0) {
                        error++;
                        alert(`二次驗證碼錯誤${error}次`);
                        if (error < 3) {
                            location.href = `captcha.php?error=${error}`
                        } else {
                            location.href = "logout.php"
                        }
                    } else if (sortnum == 1) {
                        alert('二次驗證碼通過');
                        location.href = "work_hidden.php"
                    }
                }
            })
        }

        $(function() {
            updateUI();
            $("#resetUI").click(function() {
                (num.length) = 0
                $(".box").css({
                    "background-color": "#efefef"
                })
            })
        })
    </script>
</body>

</html>