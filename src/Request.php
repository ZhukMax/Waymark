<?php

namespace Zhukmax\Waymark;

use ReflectionException;
use ReflectionMethod;

/**
 * Class Request
 * @package Zhukmax\Waymark
 */
class Request
{
    /**
     * @param string $name
     * @return null|string
     */
    public static function get(string $name): ?string
    {
        $string = trim(str_replace(['\'', '"'], ['\\\'', '\\"'], $_REQUEST[$name]));
        return strlen($string) > 0 ? $string : null;
    }

    /**
     * @param string $name
     * @param bool $default
     * @return bool
     */
    public static function getBool(string $name, bool $default = false): bool
    {
        return isset($_REQUEST[$name]) ? (bool)$_REQUEST[$name] : $default;
    }

    /**
     * @param string $name
     * @param int|null $min
     * @param int|null $max
     * @param int $default
     * @return int
     */
    public static function getInt(string $name, int $min = null, int $max = null, int $default = 0): int
    {
        $options = [
            'options' => [
                'default' => $default,
                'min_range' => $min ?: -1 * pow(10, 10),
                'max_range' => $max ?: pow(10, 10)
            ],
            'flags' => FILTER_FLAG_ALLOW_OCTAL
        ];
        
        return (int)filter_var($_REQUEST[$name], FILTER_VALIDATE_INT, $options);
    }
    
    /**
     * @param string $name
     * @return string
     */
    public static function getEmail(string $name = 'email'): string
    {
        return filter_var(self::get($name), FILTER_VALIDATE_EMAIL) ?: '';
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getArray(string $name): array
    {
        if (is_string($_REQUEST[$name])) {
            $data = (self::isJson($_REQUEST[$name])) ?
                json_decode($_REQUEST[$name], true) :
                explode(',', $_REQUEST[$name]);
        } else {
            $data = $_REQUEST[$name];
        }

        return is_array($data) ? $data : [];
    }

    /**
     * @return array
     */
    public static function getFiles(): array
    {
        return $_FILES;
    }

    /**
     * @return array
     */
    public static function getImages(): array
    {
        return array_values(array_filter($_FILES, function ($file) {
            return (bool)preg_match('/image\/.*/i', $file['type']);
        }));
    }

    /**
     * @param string $class
     * @param string $method
     * @return array
     * @throws ReflectionException
     */
    public static function getArgs(string $class, string $method): array
    {
        $reflection = new ReflectionMethod($class, $method);

        foreach($reflection->getParameters() AS $arg) {
            $args[$arg->name] = self::getArgByType($arg);
        }

        return $args ?? [];
    }

    /**
     * @param \ReflectionParameter $arg
     * @return bool|int|mixed|string|null
     */
    private static function getArgByType(\ReflectionParameter $arg)
    {
        try {
            $default = $arg->getDefaultValue();
        } catch (ReflectionException $e) {
            $default = null;
        }

        if (!$arg->getType()) {
            return self::get($arg->getName()) ?? $default;
        }

        switch ($arg->getType()->getName()) {
            case 'string':
                return self::get($arg->getName()) ?? $default;

            case 'int':
                return self::getInt($arg->getName(), null, null, $default ?? 0);

            case 'bool':
                return self::getBool($arg->getName(), $default ?? false);

            default:
                return self::get($arg->getName()) ?? $default;
        }
    }

    /**
     * @param string $string
     * @return bool
     */
    private static function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
