<?php
/**
 * Create Routers Here.
 * Allowed Methods - GET, POST, PUT, DELETE
 * Allowed callback specified format: string format or as a function.
 * 
 * string format - functionname@moduler>path e.g.:"view@Admin>Dashboard"
 */
use Core\Router;
use Core\Helpers;

$router = new Router();

$router->get("/", function(){
    echo "Get Router is Working";
    return;
});
$router->get("/test", function(){
    Helpers::coreErrorLog("Testing Routers is working.");
    echo "Testing Routers is working.";
    return;
});
$router->post("/", function(){
    Helpers::coreErrorLog("Post Router is Working");
    return;
});

$router->get("/dashboard", "view@Admin>Dashboard");
$router->get("/mainlayout", "view@MainLayout");

$router();

echo "<br>Router file is Working";