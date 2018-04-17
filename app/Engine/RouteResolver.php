<?php
/**
 * Created by PhpStorm.
 * User: xiodine
 * Date: 2018-04-17
 * Time: 21:51
 */

namespace Engine;


class RouteResolver
{
    public static function getController(Request $request)
    {
        $string = self::getControllerString($request);
        return [self::getControllerName($request, $string), self::getControllerMethod($request, $string)];
    }

    private static function getControllerString(Request $request)
    {
        // "/" must match "" and "/"
        // "" must match "/" and "/"
        // "/hello" must match "hello" and "hello/"

        if (empty(Route::$routes[$request->type])) return "";


        $array = Route::$routes[$request->type];
        $valid_indexes = [];
        $valid_values = [];
        foreach ($array as $path => $value) {
            $valid_indexes[] = $path;
            $valid_values[$path] = $value;
        }

        for ($request_index = 0; $request_index < count($request->path); $request_index++) {
            $request_path = $request->path[$request_index];
            $valid_indexes = array_filter($valid_indexes, function ($item) use ($request_path) {
                // TODO: maybe add {object} here?
                return $item === $request_path;
            });
            $valid_values = array_filter($valid_values, function ($item) use ($request_path) {
                return $item === $request_path;
            }, ARRAY_FILTER_USE_KEY);

            foreach ($valid_indexes as $index) {
                $value = $valid_values[$index];
//                var_dump("index", $index, "value", $value);
                unset($valid_values[$index]);
//                var_dump("valid_values before merge", $valid_values);
                $valid_values += $value;
//                var_dump("valid_values after merge", $valid_values);
            }
            $valid_indexes = [];
            foreach (array_keys($valid_values) as $value) {
                if (!is_numeric($value)) {
                    $valid_indexes[] = $value;
                }
            }
//            var_dump("valid_indexes", $valid_indexes, "request_path", $request_path, "valid_values", $valid_values);
        }

        $possibilities = array_values($valid_values);
        $first_possibility = current($possibilities);
        if (!is_array($first_possibility)) {
//            var_dump("DONE", $first_possibility);
            return $first_possibility;
        }
        // cry
//        var_dump("CRY!");

        return "";
    }
    
    private static function getControllerName(Request $request, $string = null)
    {
        if ($string === null) {
            $string = self::getControllerString($request);
        }
        if (empty($string)) return "";

        return substr($string, 0, strpos($string, "@"));
    }

    private static function getControllerMethod(Request $request, $string = null)
    {
        if ($string === null) {
            $string = self::getControllerString($request);
        }
        if (empty($string)) return "";

        return substr($string, strpos($string, "@") + 1);
    }

    public static function parseRouteArray($uri)
    {
        return explode("/", trim($uri, '/'));
    }

    public static function parseRoute($route)
    {
        if (is_array($route)) {
            $route = join("/", $route);
        }
        return '/' . trim($route, '/');
    }
}