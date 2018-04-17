<?php
/**
 * Created by PhpStorm.
 * User: xiodine
 * Date: 2018-04-17
 * Time: 21:47
 */

namespace Engine;


class Route
{
    public static $routes = [
        'get' => [],
        'post' => [],
    ];
    
    public static function get($route, $controller)
    {
        self::add('get', $route, $controller);
    }

    public static function post($route, $controller)
    {
        self::add('post', $route, $controller);
    }

    public static function add($method, $route, $controller)
    {
        $routeArray = RouteResolver::parseRouteArray($route);

        $current = &self::$routes[$method];

        foreach ($routeArray as $item) {
            if (empty($current[$item])) {
                $current[$item] = [];
            }
            $current = &$current[$item];
        }

        array_unshift($current, $controller);
    }
}