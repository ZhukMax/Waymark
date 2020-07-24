<?php

namespace Zhukmax\Waymark\Tests;

/**
 * Class Helper
 * @package Zhukmax\Waymark\Tests
 */
class Helper
{
    /**
     * @param string $className
     * @param string $methodName
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    public static function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @return \ReflectionProperty
     * @throws \ReflectionException
     */
    public static function getPrivateProperty(string $className, string $propertyName): \ReflectionProperty
    {
        $reflector = new \ReflectionClass($className );
        $property = $reflector->getProperty( $propertyName );
        $property->setAccessible( true );

        return $property;
    }
}
