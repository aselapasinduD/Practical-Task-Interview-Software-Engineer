<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../config.php";

spl_autoload_register(function($class){
    if(str_contains($class, '\\') || strpos($class, '\\') > 0){
        $class2 = explode('\\', $class);
        $class = end($class2);
    }

    /**
     * Autoload Core Files (Do not delete core files)
     * 
     * @since 1.0.0
     */
    $coreClassPaths = [
        ROOT_DIR . 'core/Controller.php',
        ROOT_DIR . 'core/Views.php',
        ROOT_DIR . 'core/Router.php',
    ];

    /**
     * Autoload Model class files
     * 
     * @since 1.0.0
     */
    $paths = [
        ROOT_DIR . 'app/models/' . $class . '.model.php',
        ROOT_DIR . 'app/controllers/' . $class . '.controller.php'
    ];

    foreach ($coreClassPaths as $path){
        if (is_readable($path)){
            include_once $path;
        }
    }
    foreach ($paths as $path){
        if(is_readable($path)){
            include_once $path;
        }
    }
});