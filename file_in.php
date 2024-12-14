<?php
require_once("db.php");
if ($_FILES["file"]["name"] != "") {
    $all = file_get_contents($_FILES["file"]["tmp_name"]);
    $rows_data = iconv("big5", "UTF-8", $all);
    $rows = explode("\n", $rows_data);
    foreach ($rows as $row) {
        if(!empty($row)){
            $data = explode(",", $row);
            ins("work", [
                "user_id" => $_SESSION["user"]["id"],
                "name" => $data[0],
                "date" => $data[1],
                "start_time" => $data[2],
                "end_time" => $data[3],
                "mode" => $mode2[$data[4]],
                "order" => $order2[$data[5]],
                "des" => $data[6],
            ]);
        }
    }
}
if(isset($_SESSION["get"])){
    header("location:work.php?".$_SESSION["get"]);
}else{
    header("location:work_hidden.php");
}