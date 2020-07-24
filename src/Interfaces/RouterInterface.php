<?php

namespace Zhukmax\Waymark\Interfaces;

/**
 * Interface RouterInterface
 * @package Zhukmax\Waymark
 */
interface RouterInterface
{
    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function get(string $path, string $class, string $action, string $type = 'json'): self;

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function post(string $path, string $class, string $action, string $type = 'json'): self;

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function put(string $path, string $class, string $action, string $type = 'json'): self;

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function delete(string $path, string $class, string $action, string $type = 'json'): self;

    /**
     * @param string $path
     * @param string $class
     * @param string $action
     * @param string $type
     * @return RouterInterface
     */
    public function all(string $path, string $class, string $action, string $type = 'html'): self;

    public function output();
}
