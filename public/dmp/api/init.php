<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$apiClassFile = 'dmp.class.php';
$phpVersion = (float) phpversion();

if ($phpVersion < 5.6) {
    $errorMessage = 'Needed PHP Version >= 5.6. '.
                    'Current Version Is '.strval($phpVersion);
    throw new Exception($errorMessage);
}

if ($phpVersion < 7.1) {
    $apiClassFile = 'dmp.compatibility.class.php';
}

require_once(__DIR__.'/classes/'.$apiClassFile);

unset($apiClassFile);
unset($phpVersion);
?>