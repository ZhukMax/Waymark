<?php

namespace Zhukmax\SimpleRouter;

use Zhukmax\SimpleRouter\Types\Json;
use Zhukmax\SimpleRouter\Types\TypeInterface;

/**
 * Class AbstractRouter
 * @package Zhukmax\SimpleRouter
 */
abstract class AbstractRouter implements RouterInterface
{
    /** @var TplEngineInterface Template engine's object */
    public $tplEngine;
    /** @var array */
    protected $routes;
    /** @var TypeInterface */
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

        if (!strpos($activeRoute['class'], '\\') && isset($this->namespace)) {
            $className = $this->namespace . '\\' . $activeRoute['class'];
        } else {
            $className = $activeRoute['class'];
        }

        if (!class_exists($className)) {
            throw new Exception("Controller is not exists");
        }

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
        $this->type = new Json();
    }

    protected final function finishWork(): void
    {
        $this->type->header();
        $this->type->body($this->output);
        exit;
    }

    /**
     * @param string $activeRouteType
     * @throws Exception
     */
    private function setType(string $activeRouteType): void
    {
        /** @var string $type */
        $type = "\\Zhukmax\\SimpleRouter\\Types\\" . ucfirst($activeRouteType);
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
        return '/' . str_replace(['/', '{int}', '{str}'], ['\\/', '(\d+)', '(\w+)'], $path) . '$/';
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
                break;
            }
        }

        if (!isset($activeRoute)) {
            throw new Exception("Wrong method");
        }

        if (!in_array(strtolower($_SERVER["REQUEST_METHOD"]), $activeRoute['method'])) {
            throw new Exception("Wrong HTTP method: " . $activeRoute['method']);
        }

        return $activeRoute;
    }
}
