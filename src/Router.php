<?php

namespace Zhukmax\SimpleRouter;

/**
 * Class Router
 * @package Zhukmax\SimpleRouter
 */
class Router extends AbstractRouter
{
    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function get(string $path, string $class, string $action, string $type = 'json'): RouterInterface
    {
        return $this->setRoute($path, $class, $action, ['get'], $type);
    }

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function post(string $path, string $class, string $action, string $type = 'json'): RouterInterface
    {
        return $this->setRoute($path, $class, $action, ['post'], $type);
    }

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function put(string $path, string $class, string $action, string $type = 'json'): RouterInterface
    {
        return $this->setRoute($path, $class, $action, ['put'], $type);
    }

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function delete(string $path, string $class, string $action, string $type = 'json'): RouterInterface
    {
        return $this->setRoute($path, $class, $action, ['delete'], $type);
    }

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function all(string $path, string $class, string $action, string $type = 'html'): RouterInterface
    {
        return $this->setRoute($path, $class, $action, ['get', 'post', 'put', 'delete'], $type);
    }

    public function output()
    {
        try {
            $this->executeRoute();
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->finishWork();
    }
}
