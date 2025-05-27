<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoloader.php';

if(defined(ROOT_DIR)){
    echo "Error: Not Define The Root.";
    return;
}

include ROOT_DIR . "app/routes/router.php";

// echo $_SERVER["DOCUMENT_ROOT"] . "\n";
// echo ROOT_DIR;