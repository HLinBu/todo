<?php
require_once("db.php");
if (!empty($_GET["id"])) {
    upd("work", [
        "start_time" => $_GET["start"],
        "end_time" => $_GET["end"],
    ], [
        "id" => $_GET["id"]
    ]);
} else {
    if (!empty($_SESSION["work"])) {
        $work = $_SESSION["work"];
    } else {
        $work = sels("work", ["user_id" => $_SESSION["user"]["id"], "date" => date("Y-m-d")]);
    }
    echo json_encode($work);
}
