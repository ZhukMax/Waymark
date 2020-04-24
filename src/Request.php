<?php

namespace Zhukmax\SimpleRouter;

use ReflectionMethod;

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
        return $_REQUEST[$name] ?? null;
    }
    
    /**
     * @param string $name
     * @param int|null $min
     * @param int|null $max
     * @return int
     */
    public static function getInt(string $name, int $min = null, int $max = null)
    {
        $options = [
            'options' => [
                'default' => 0,
                'min_range' => $min ?: -1 * pow(10, 10),
                'max_range' => $max ?: pow(10, 10)
            ],
            'flags' => FILTER_FLAG_ALLOW_OCTAL
        ];
        
        return filter_var($_REQUEST[$name], FILTER_VALIDATE_INT, $options);
    }
    
    /**
     * @param string $name
     * @return string
     */
    public static function getEmail(string $name)
    {
        return filter_var($_REQUEST[$name], FILTER_VALIDATE_EMAIL) ?: '';
    }

    /**
     * @param string $class
     * @param string $method
     * @return array
     * @throws \ReflectionException
     */
    public static function getArgs(string $class, string $method): array
    {
        $args = [];
        $reflection = new ReflectionMethod($class, $method);

        foreach($reflection->getParameters() AS $arg) {
            if($_REQUEST[$arg->name]) {
                $args[$arg->name] = $_REQUEST[$arg->name] ?? null;
            }
        }

        return $args;
    }
}
