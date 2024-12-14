<?php
header('Content-Type: image/svg+xml');
?>
<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30">
    <text x="0" y="25" style="font-size: 25px; font-family: monospace"><?=$_GET['text']?></text>
</svg>