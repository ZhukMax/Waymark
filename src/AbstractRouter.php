<?php

namespace Zhukmax\Waymark;

/**
 * Class AbstractRouter
 * @package Zhukmax\Waymark
 */
abstract class AbstractRouter implements Interfaces\RouterInterface
{
    /** @var Interfaces\TplEngineInterface Template engine's object */
    public $tplEngine;
    /** @var array */
    protected $routes;
    /** @var Types\TypeInterface */
    protected $type;
    /** @var string|array */
    protected $output;
    /** @var string */
    protected $namespace;

    /**
     * AbstractRouter constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->tplEngine = $params['tplEngine'] ?? null;
        $this->namespace = $params['namespace'] ?? null;

        if ($params['routes']) {
            if (is_file($params['routes'])) {
                $routesGroups = json_decode(file_get_contents($params['routes']), true);
            }

            try {
                $this->routesFromArray($routesGroups ?? $params['routes']);
            } catch (Exception $e) {
                $this->error($e->getMessage());
                $this->finishWork();
            }
        }
    }

    /**
     * @param array $routesGroups
     * @throws Exception
     */
    protected function routesFromArray(array $routesGroups)
    {
        foreach ($routesGroups as $key => $group) {
            foreach ($group as $path => $item) {
                /** @var string $type */
                $type = $item[2] ?? 'json';

                if (!method_exists($this, $key)) {
                    throw new Exception("Not exist method: " . $key);
                }
                $this->$key($path, $item[0] . 'Controller', $item[1], $type);
            }
        }
    }

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param array $methods
     * @param string $type
     * @return Router
     */
    protected function setRoute(string $path, string $class, string $action, array $methods, string $type): self
    {
        /** @var string $regex */
        $regex = self::routeToRegex($path);
        $this->routes[$regex] = [
            'class'  => $class,
            'action' => $action,
            'method' => $methods,
            'type'   => $type
        ];

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function executeRoute(): void
    {
        /** @var array $activeRoute */
        $activeRoute = $this->getActiveRoute();
        $className = $this->controllerName($activeRoute);

        /** @var AbstractController $controller */
        $controller = (new $className($this->tplEngine));
        $action = $activeRoute['action'];
        $this->setType($activeRoute['type']);

        try {
            /** @var array $args */
            $args = Request::getArgs($className, $action);
            $this->output = call_user_func_array([$controller, $action], $args);
        } catch (\ReflectionException $e) {
            $this->error($e->getMessage());
            $this->finishWork();
        }
    }

    /**
     * @param string $message
     */
    protected final function error(string $message): void
    {
        $this->output['status'] = "Error";
        $this->output['error'] = $message;
        $this->type = new Types\Json();
    }

    protected final function finishWork(): void
    {
        $this->type->header();
        $this->type->body($this->output);
        exit;
    }

    /**
     * @param array $activeRoute
     * @return string
     * @throws Exception
     */
    private function controllerName(array $activeRoute): string
    {
        if (!strpos($activeRoute['class'], '\\') && isset($this->namespace)) {
            $className = $this->namespace . '\\' . $activeRoute['class'];
        } else {
            $className = $activeRoute['class'];
        }

        if (!class_exists($className)) {
            throw new Exception("Controller is not exists");
        }

        return $className;
    }

    /**
     * @param string $activeRouteType
     * @throws Exception
     */
    private function setType(string $activeRouteType): void
    {
        /** @var string $type */
        $type = "\\Zhukmax\\Waymark\\Types\\" . ucfirst($activeRouteType);
        if (!class_exists($type)) {
            throw new Exception("Type class is not exists");
        }
        $this->type = new $type();
    }

    /**
     * @param string $path
     * @return string
     */
    private static final function routeToRegex(string $path): string
    {
        preg_match_all('/{\w+:\w+}/', $path, $matches);
        if ($matches[0]) {
            $reqPieces = explode('/', $path);
        }

        foreach ($matches[0] as $match) {
            $data = explode(':', str_replace(['{', '}'], '', $match));
            $path = str_replace($match, '{'.$data[1].'}', $path);
            $_REQUEST[$data[0]] = $match . array_search($match, $reqPieces ?? []);
        }
        return '/' . str_replace(['/', '{int}', '{str}'], ['\\/', '(\d+)', '(\S+)'], $path) . '\/*/';
    }

    /**
     * @return array
     * @throws Exception
     */
    private final function getActiveRoute(): array
    {
        $requestPath = explode("?", $_SERVER["REQUEST_URI"])[0];
        foreach ($this->routes as $path => $route) {
            if (preg_match($path, $requestPath)) {
                $activeRoute = $route;
                $this->setArgs($requestPath);
                break;
            }
        }

        if (!isset($activeRoute)) {
            throw new Exception("Wrong url: hasn't equal route");
        }

        if (!in_array(strtolower($_SERVER["REQUEST_METHOD"]), $activeRoute['method'])) {
            throw new Exception("Wrong HTTP method: " . $activeRoute['method']);
        }

        return $activeRoute;
    }

    /**
     * @param string $requestPath
     */
    private function setArgs(string $requestPath): void
    {
        $reqPieces = explode('/', $requestPath);
        foreach($_REQUEST as $key => $item) {
            if (preg_match('/{\w+:\w+}/', $item, $match)) {
                $i = (int)str_replace($match[0], '', $_REQUEST[$key]);
                $_REQUEST[$key] = $reqPieces[$i];
            }
        }
    }
}
