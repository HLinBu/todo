<?php
require_once("db.php");
$works = sels("work", ["user_id" => $_GET["id"]]);
header("Content-Type:text/csv; charset=BIG-5");
header("Content-Transfer-Encoding: UTF-8");
header("Content-Disposition-attachment;filename=text/csv");
foreach ($works as $key => $work) {
    $content = $work["name"] . "," . $work["date"] . "," . $work["start_time"] . "," . $work["end_time"] . "," . $mode[$work["mode"]] . "," . $order[$work["order"]] . "," . $work["des"];
    echo iconv("UTF-8", "big5", $content);
    if (($key + 1) != count($works)) {
        echo "\n";
    }
}
