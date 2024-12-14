<?php
require_once("db.php");
if(isset($_SESSION["work"])){
    unset($_SESSION["work"]);
    unset($_SESSION["get"]);
}
if(isset($_SESSION["tmp_user"])){
    $_SESSION["user"]=$_SESSION["tmp_user"];
    unset($_SESSION["tmp_user"]);
    ins("record", ["mode" => "登入", "user_id" => $_SESSION["user"]["id"]]);
}
header("location:work.php");
?>