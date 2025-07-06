<?php
// File: core/Router.php

class Router {
    protected static $routes = [];

    /**
     * Menambahkan rute baru ke dalam koleksi.
     */
    public static function add($method, $uri, $controller) {
        self::$routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
        ];
    }

    /**
     * Mencari dan menjalankan controller yang sesuai dengan URL yang diminta.
     */
    public static function route($url) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            if ($route['uri'] === $url && $route['method'] === $requestMethod) {
                
                // Pecah string controller menjadi nama class dan nama method
                list($controllerName, $methodName) = explode('@', $route['controller']);
                
                // Path ke file controller
                $controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;

                    if (class_exists($controllerName)) {
                        $controllerInstance = new $controllerName();

                        if (method_exists($controllerInstance, $methodName)) {
                            // Panggil method controller
                            return $controllerInstance->$methodName();
                        }
                    }
                }
            }
        }

        // Jika tidak ada rute yang cocok, lemparkan exception
        throw new Exception('No route found for URI: ' . $url);
    }
}
