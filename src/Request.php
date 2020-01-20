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
                'default' => 0
            ]
        ];
        
        if (isset($min) || $min == 0) {
            $options['options']['min_range'] = $min;
        }
        if (isset($max) || $max == 0) {
            $options['options']['max_range'] = $max;
        }
        
        return $_POST[$name] ? filter_var($_POST[$name], FILTER_VALIDATE_INT, $options)) :
            filter_var($_GET[$name], FILTER_VALIDATE_INT, $options)) ?: 0;
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
