<?php
    require_once(__DIR__.'/api/init.php');

    $isDebug = false;
    $dmpAPI = new DmpAPI($isDebug);
    $dmpAPI->send();
?>