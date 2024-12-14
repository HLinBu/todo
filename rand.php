<?php 
require_once('db.php');
$str = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopassdfghjklzxcvbnm0123456789";
$str=  str_split($str);
shuffle($str);
$array = array_splice($str,0,4);
$rand = rand(0,1);
echo json_encode([$array,$rand]);
sort($array);
if($rand){
    $array = array_reverse($array);
}
$_SESSION['captcha'] = join('',$array);