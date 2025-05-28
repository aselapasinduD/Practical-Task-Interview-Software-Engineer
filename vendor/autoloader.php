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
        ROOT_DIR . '/core/Controller.php',
        ROOT_DIR . '/core/Views.php',
        ROOT_DIR . '/core/Router.php',
        ROOT_DIR . '/core/Helpers.php'
    ];

    foreach ($coreClassPaths as $path){
        if (is_readable($path)){
            require_once $path;
        }
    }

    /**
     * Autoload modules that use HMVC
     * 
     * @since 1.0.0
     */
    $baseDir = ROOT_DIR . '/app';
    $folders = ['modules'];

    // $paths = [
    //     ROOT_DIR . 'app/models/' . $class . '.model.php',
    //     ROOT_DIR . 'app/controllers/' . $class . '.controller.php'
    // ];
    // foreach ($paths as $path){
    //     if(is_readable($path)){
    //         include_once $path;
    //     }
    // }

    foreach ($folders as $folder) {
        $directory = $baseDir . "/" . $folder;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                require_once $file->getPathname();
            }
        }
    }
});