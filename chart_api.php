<?php
require_once("db.php");

if (!empty($_SESSION["choose"])) {
    $data = [];
    for ($i = 0; $i < 3; $i++) {
        $array = [];
        for ($index = 0; $index < 2; $index++) {
            if($_SESSION["choose"]=="mode"){
                $text = $mode[$i];
            }else{
                $text = $order[$i];
            }
            $array[0] = $text;
            if (!empty($_SESSION["date"])) {
                $array[1] = count(sels("work", ["user_id" => $_SESSION["user"]["id"], "date" => $_SESSION["date"], $_SESSION["choose"] => $i]));
            } else {
                $array[1] = count(sels("work", ["user_id" => $_SESSION["user"]["id"], $_SESSION["choose"] => $i]));
            }
        }
        $data[] = $array;
    }
    echo json_encode($data);
}
