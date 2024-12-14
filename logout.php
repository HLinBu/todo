<?php
require_once("db.php");
ins("record",["user_id"=>$_SESSION["user"]["id"],"mode"=>"登出"]);
session_unset();
header("location:index.php");