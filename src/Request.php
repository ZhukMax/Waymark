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
        
        return $_POST[$name] ? filter_var($_POST[$name], FILTER_VALIDATE_INT, $options) :
            filter_var($_GET[$name], FILTER_VALIDATE_INT, $options);
    }
    
    /**
     * @param string $name
     * @return string
     */
    public static function getEmail(string $name)
    {
        return $_POST[$name] ? filter_var($_POST[$name], FILTER_VALIDATE_EMAIL) :
            filter_var($_GET[$name], FILTER_VALIDATE_EMAIL) ?: '';
    }
}
