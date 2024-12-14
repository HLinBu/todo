<?php
header("Content-Type:image/svg+xml");
?>
<svg style="background-color:gray;" width="30px" height="30px" xmlns="http://www.w3.org/2000/svg">
    <text style="fill:white" x="10" y="20"><?= $_GET["index"] ?></text>
</svg>