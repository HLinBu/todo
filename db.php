<?php
if (!isset($_SESSION)) {
    session_start();
}
$db = mysqli_connect("127.0.0.1", "root", "", "todo");
if (!$db) {
    die(mysqli_connect_error());
}
$db->query("SET NAMES UTF8");
function exArray($data, $s = ",", $c = "=")
{
    global $db;
    $array = [];
    if (is_array(($data))) {
        foreach ($data as $k => $v) {
            $v = $db->real_escape_string("$v");
            $sql = "`$k`$c'$v'";
            $array[] = $sql;
        }
    } else {
        $array[] = $data;
    }
    $array = join($s, $array);
    return $array;
}
function sel($table, $where = "1")
{
    global $db;
    $w = exArray($where, " AND ");
    $sql = "SELECT * FROM `$table` WHERE $w";
    $result = $db->query($sql);
    return $result->fetch_assoc();
}
function sels($table, $where = "1", $ex = "")
{
    global $db;
    $array = [];
    $w = exArray($where, " AND ");
    $sql = "SELECT * FROM `$table` WHERE $w $ex";
    $result = $db->query($sql);
    while ($r = $result->fetch_assoc()) {
        $array[] = $r;
    }
    return $array;
}
function ins($table, $data)
{
    global $db;
    $d = exArray($data);
    $sql = "INSERT INTO `$table` SET $d";
    $db->query($sql);
    return $db->insert_id;
}
function upd($table, $data, $where)
{
    global $db;
    $d = exArray($data);
    $w = exArray($where, " AND ");
    $sql = "UPDATE `$table` SET $d WHERE $w";
    $db->query($sql);
}
function del($table, $where)
{
    global $db;
    $w = exArray($where, " AND ");
    $sql = "DELETE FROM `$table` WHERE $w";
    $db->query($sql);
}
function dd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
$mode = ["未處理", "處理中", "已完成"];
$order = ["普通件", "速件", "最速件"];
$mode2 = ["未處理" => 0, "處理中" => 1, "已完成" => 2];
$order2 = ["普通件" => 0, "速件" => 1, "最速件" => 2];
