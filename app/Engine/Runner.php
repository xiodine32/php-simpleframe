<?php
/**
 * Created by PhpStorm.
 * User: xiodine
 * Date: 2018-04-17
 * Time: 21:29
 */

namespace {
    if (!function_exists("view")) {
        function view($route, $parameters)
        {
            $path = __DIR__ . '/../../views/';
            $path .= str_replace(".", "/", $route);
            $path .= ".php";
            $callback = function ($__path, $__data) {
                extract($__data);
                /** @noinspection PhpIncludeInspection */
                require $__path;
            };
            $callback($path, $parameters);
        }
    }
}

namespace Engine {

    class Runner
    {
        /**
         * @var Request
         */
        public $route;

        /**
         * @return Request
         */
        private function request()
        {
            $uri = trim($_SERVER['REQUEST_URI'], '/');
            if (($q = strpos($uri, "?")) !== false) {
                $uri = substr($uri, 0, $q);
            }
            $request = new Request();

            $request->path = RouteResolver::parseRouteArray($uri);

            $request->type = mb_strtolower($_SERVER['REQUEST_METHOD']);
            $request->gets = $_GET;
            $request->posts = $_POST;
            $request->files = $_FILES;

            return $request;
        }

        public function run()
        {
            require __DIR__ . '/../routing.php';
            $class = $this->request();
            list($class, $method) = RouteResolver::getController($class);
            if (empty($class) || empty($method)) {
                die("TODO: 404");
            }

            $class_name = "\\Controllers\\$class";
            $class = new $class_name();
            $class->$method();
        }
    }
}