<?php

namespace Zhukmax\SimpleRouter;

/**
 * Class AbstractRouter
 * @package Zhukmax\SimpleRouter
 */
abstract class AbstractRouter implements RouterInterface
{
    /** @var mixed Template engine's object */
    public $tplEngine;
    /** @var array */
    protected $routes;
    /** @var string */
    protected $type;
    /** @var string|array */
    protected $output;

    /**
     * AbstractRouter constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        if ($params['tplEngine']) {
            $this->tplEngine = $params['tplEngine'];
        }

        if ($params['routes']) {
            if (is_file($params['routes'])) {
                $routesGroups = json_decode(file_get_contents($params['routes']), true);
            }

            $this->routesFromArray($routesGroups ?? $params['routes']);
        }
    }

    /**
     * @param array $routesGroups
     */
    protected function routesFromArray(array $routesGroups)
    {
        foreach ($routesGroups as $key => $group) {
            foreach ($group as $path => $item) {
                $this->$key($path, $item[0] . 'Controller', $item[1]);
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
        $regex = self::routeToRegex($path);
        $this->routes[$regex] = [
            'class'  => $class,
            'action' => $action,
            'method' => $methods,
            'type'   => $type
        ];

        return $this;
    }

    protected function getType()
    {
        switch ($this->type) {
            case 'json':
                header('Content-Type: application/json');
                break;

            case 'csv':
                $date = date("Ymd_Hms");
                header("Content-type: text/csv");
                header("Content-Disposition: attachment; filename=$date.csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                break;

            case 'html':
                header("Content-Type: text/html");
                break;

            case 'text':
                header("Content-Type: text/plain");
                break;

            default:
                header('Content-Type: application/json');
                break;
        }
    }

    protected function executeRoute()
    {
        try {
            $activeRoute = $this->getActiveRoute();
        } catch (\Exception $e) {
            $this->output['status'] = "Error";
            $this->output['error'] = $e->getMessage();

            return null;
        }

        $class = $activeRoute['class'];
        $action = $activeRoute['action'];
        $this->type = $activeRoute['type'];

        $this->output = $class::$action($this->tplEngine);
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
     * @throws \Exception
     */
    private final function getActiveRoute(): array
    {
        $requestPath = str_replace("?".$_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]);
        foreach ($this->routes as $path => $route) {
            if (preg_match($path, $requestPath)) {
                $activeRoute = $route;
                break;
            }
        }

        if (!isset($activeRoute)) {
            throw new \Exception("Wrong method");
        }

        if (!in_array(strtolower($_SERVER["REQUEST_METHOD"]), $activeRoute['method'])) {
            throw new \Exception("Wrong HTTP method: " . $activeRoute['method']);
        }

        return $activeRoute;
    }
}
