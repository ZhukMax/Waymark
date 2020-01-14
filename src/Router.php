<?php

namespace Zhukmax\SimpleRouter;

/**
 * Class Router
 * @package Zhukmax\SimpleRouter
 */
class Router extends AbstractRouter implements RouterInterface
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
        $this->executeRoute();
        $this->getType();

        switch ($this->type) {
            case 'json':
                print_r(json_encode($this->output, JSON_UNESCAPED_UNICODE));
                break;

            case 'csv':
                foreach ($this->output as $item) {
                    echo $item . "\n";
                }
                break;

            case 'html':
                print_r($this->output);
                break;

            case 'text':
                print_r($this->output);
                break;

            default:
                print_r(json_encode($this->output, JSON_UNESCAPED_UNICODE));
                break;
        }
        exit;
    }
}
