<?php
require_once("db.php");
$_SESSION["answer"] = "";
$data = [];
for ($i = 0; $i < 4; $i++) {
    $mode = rand(0, 1);
    if ($mode == 0) {
        $rand = chr(rand(65, 90));
    }
    if ($mode == 1) {
        $rand = rand(0, 9);
    }
    $data[] = $rand;
    $_SESSION["answer"] .= $rand;
}
echo json_encode($data);
