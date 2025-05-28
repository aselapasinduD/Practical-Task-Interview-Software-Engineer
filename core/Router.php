<?php

namespace Core;

use Core\Helpers;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Router Core Class
 * 
 * @since 1.0.0
 */
class Router{
    private array $routes;
    private bool $installed;
    private $routeFound;
    private $methodFound;

    /**
     * Registers a GET Routers
     * 
     * @param string $route - the end point for the GET request.
     * @param callable|string $callback - the handle function or controller method.
     * @since 1.0.0
     */
    public function get(string $route, callable|string $callback)
    {
        $this->routes[strtolower($route)]["GET"] = $callback;
    }

    /**
     * Registers a New POST Router
     * 
     * @param string $route - the end point for the POST request.
     * @param callable|string $callback - the handle function or controller method.
     * @since 1.0.0
     */
    public function post(string $route, callable|string $callback)
    {
        $this->routes[strtolower($route)]["POST"] = $callback;
    }

    /**
     * Registers a New PUT Router
     * 
     * @param string $route - the end point for the PUT request.
     * @param callable|string $callback - the handle function or controller method.
     * @since 1.0.0
     */
    public function put(string $route, callable|string $callback)
    {
        $this->routes[strtolower($route)]["PUT"] = $callback;
    }

    /**
     * Registers a new DELETE route.
     * 
     * @param string $route - the end point for the DELETE request.
     * @param callable|string $callback - the handle function or controller method.
     * @since 1.0.0
     */
    public function delete(string $route, callable|string $callback)
    {
        $this->routes[strtolower($route)][`DELETE`] = $callback;
    }

    /**
     * Handle all the routers.
     * 
     * @since 1.0.0
     */
    public function handler($urlPath, $method)
    {
        $this->routeFound = null;
        $this->methodFound = null;

        $isURL = isset($this->routes[$urlPath]);
        $isMethod = isset($this->routes[$urlPath][$method]);

        Helpers::coreErrorLog("URL: {$urlPath} - {$isURL}, METHOD: {$method} - {$isMethod}.");

        if($isURL){
            $this->routeFound = $urlPath;
            if($isMethod){
                $this->methodFound = $method;
                
                /**
                 * Exicute string-based callback controller
                 * 
                 * @since 1.0.0
                 */
                if(is_string($this->routes[$urlPath][$method])){
                    // echo $this->routes[$urlPath][$method];

                    $modulesDirectory =  ROOT_DIR . "/app/modules";

                    $handlerClass = explode('@', $this->routes[$urlPath][$method]);
                    $handlerClass = array_values(array_filter($handlerClass));
                    $modulePath = str_replace(">", "/", $handlerClass[1]);
                    $functionName = $handlerClass[0];

                    // echo "<br/>" . $modulePath . "<br/>";
                    // echo $functionName . "<br/>";

                    $controllerFilePath = $modulesDirectory . "/" . $modulePath . '/Controller.php';

                    if(file_exists($controllerFilePath)){
                        // echo $controllerFilePath . "<br/>";
                        require_once $controllerFilePath;

                        $fullyQualifiedClassName = implode("\\", explode(" ",ucwords(implode(" ",explode("/", "/app/modules/" . $modulePath . "/Controller")))));
                        
                        if(class_exists($fullyQualifiedClassName)){
                            $class = new $fullyQualifiedClassName;
                            if(method_exists($class, $functionName)){
                                switch($method){
                                    case "GET":
                                        echo $class->$functionName();
                                        break;
                                    case "POST":
                                        echo $class->$functionName();
                                        break;
                                    case "PUT":
                                        echo $class->$functionName();
                                        break;
                                    case `DELETE`:
                                        echo $class->$functionName();
                                        break;
                                    default:
                                        Helpers::coreErrorLog("METHOD: [{$method}], ROUTE: [$urlPath] - Controller class Couldn't find for this Moduler: [{$modulePath}]");
                                        throw new \Exception("METHOD: [{$method}], ROUTE: [$urlPath] - Controller class Couldn't find for this Moduler: [{$modulePath}]");
                                        break;
                                }
                            } else {
                                Helpers::coreErrorLog("METHOD: [{$method}], ROUTE: [$urlPath] - method does not exist in the Controller class: [{$functionName}].");
                                throw new \Exception("METHOD: [{$method}], ROUTE: [$urlPath] - method does not exist in the Controller class: [{$functionName}].");
                            }
                        } else {
                            Helpers::coreErrorLog("METHOD: [{$method}], ROUTE: [$urlPath] - Controller class Couldn't find for this Moduler: [{$modulePath}]");
                            throw new \Exception("METHOD: [{$method}], ROUTE: [$urlPath] - Controller class Couldn't find for this Moduler: [{$modulePath}]");
                        }
                    } else {
                        Helpers::coreErrorLog("METHOD: [{$method}], ROUTE: [$urlPath] - Couldn't find the Moduler: [{$modulePath}].");
                        throw new \Exception("METHOD: [{$method}], ROUTE: [$urlPath] - Couldn't find the Moduler: [{$modulePath}].");
                    }

                /**
                 * Exicute callable functions
                 * 
                 * @since 1.0.0
                 */
                } else if (is_callable($this->routes[$urlPath][$method])){
                    $this->routes[$urlPath][$method]();
                } else {
                    Helpers::coreErrorLog("METHOD: [{$method}], ROUTE: [$urlPath] : Invalied Callback Methods.");
                    throw new \Exception("METHOD: [{$method}], ROUTE: [$urlPath] : Invalied Callback Methods.");
                }
            } else {
                Helpers::coreErrorLog("METHOD: [{$method}] Not Allowed in this for this ROUTE: [$urlPath]");
                throw new \Exception("METHOD: [{$method}] Not Allowed in this for this ROUTE: [$urlPath]");
            }
        } else {
            Helpers::coreErrorLog("ROUTE: [$urlPath] Not Found.");
            throw new \Exception("ROUTE: [$urlPath] Not Found.");
        }
    }

    /**
     * Invoke the requested router to execute callback functions.
     * 
     * @since 1.0.0
     */
    public function __invoke()
    {
        $requestUrl = parse_url($_SERVER['REQUEST_URI']);
        $urlPath = strtolower($requestUrl['path']);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->handler(urlPath: $urlPath, method: $requestMethod);
    }

    public function __destruct()
    {
        if(!$this->routeFound){
            http_response_code(404);
            echo "<br>This Route NOT found.";
            return;
        }
        if(!$this->methodFound){
            http_response_code(405);
            echo "<br>This method NOT allowed.";
            return;
        }
  
    }
}