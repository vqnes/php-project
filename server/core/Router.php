<?php

namespace core;

use core\base\View;
use core\Logger\LoggerInterface;
use core\DependencyInjection\Container;

class Router
{
    /**
     * @var array<string,array<string,string>>
     */
    protected static array $routes = [];

    /**
     * @var array<string,string>
     */
    protected static array $route = [];

    protected static LoggerInterface $logger;

    /**
     * @param string $regexp
     * @param array<string,string> $route
     */
    public static function add(string $regexp, array $route = []): void
    {
        self::$routes[$regexp] = $route;
    }

    protected static function checkRoute(string $url): bool
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                self::setRoute($pattern, $matches);

                return true;
            }
        }

        return false;
    }

    public static function dispatch(string $url): void
    {
        if (self::checkRoute($url)) {
            try {
                $controllerName = 'app\Controllers\\' . Str::toPascalCase(self::$route['controller']) . 'Controller';
                if (!class_exists($controllerName)) {
                    throw new \Exception("Controller $controllerName not found");
                }

                $dependencyContainer = Container::getInstance();
                $dependencyContainer->set($controllerName, $controllerName);

                $controller = $dependencyContainer->get($controllerName);

                $action = Str::toCamelCase(self::$route['action']) . 'Action';
                if (!method_exists($controller, $action)) {
                    throw new \Exception("Action $controllerName::$action not found");
                }

                $controller->$action();

                return;
            } catch (\Exception $e) {
                self::$logger->warning($e->__toString());
            }
        }

        http_response_code(404);
        (new View('error', 'http-error'))->render([
            'title'   => 'Not found',
            'code'    => 404,
            'message' => 'Page not found',
        ]);
    }

    /**
     * Метод для извлечения явных гет-параметров из адресной строки браузера
     */
    protected static function removeQueryString(string $queryString): string
    {
        if (mb_strlen($queryString) !== 0) {
            $params = explode('&', $queryString, 2);

            if (strpos($params[0], '=') === false) {
                return rtrim($params[0], '/');
            }
        }

        return '';
    }

    /**
     * @param string $routeKey
     * @param array<string|int,string> $params
     * 
     * @return void
     */
    protected static function setRoute(string $routeKey, array $params = []): void
    {
        $route = self::$routes[$routeKey];

        foreach ($params as $key => $value) {
            if (is_string($key) && !isset($route[$key])) {
                $route[$key] = $value;
            }
        }

        if (!isset($route['controller'])) {
            throw new \Exception('Route controller not defined');
        }

        if (!isset($route['action'])) {
            $route['action'] = 'index';
        }

        self::$route = $route;
    }

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * for debug
     * @return array<string,array<string,string>>
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * for debug
     * @return array<string,string>
     */
    public static function getRoute(): array
    {
        return self::$route;
    }
}
