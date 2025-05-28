<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoloader.php';

if(defined(ROOT_DIR)){
    echo "Error: Not Define The Root.";
    return;
}

require_once(ROOT_DIR . "/app/routes/routes.php");

// echo $_SERVER["DOCUMENT_ROOT"] . "\n";
// echo ROOT_DIR;