<?php

namespace Zhukmax\SimpleRouter;

/**
 * Class Request
 * @package Zhukmax\SimpleRouter
 */
class Request
{
    /**
     * @param string $name
     * @return mixed
     */
    public static function get(string $name)
    {
        return $_POST[$name] ? $_POST[$name] :
            $_GET[$name] ?: null;
    }
}
