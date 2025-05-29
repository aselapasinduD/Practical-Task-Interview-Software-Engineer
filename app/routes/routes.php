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

$router->get("/login", "view@Users>Login");

$router->get("/dashboard", "view@Admin>Dashboard");
$router->get("/mainlayout", "view@MainLayout");

$router();