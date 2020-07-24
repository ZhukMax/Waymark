<?php

namespace Zhukmax\Waymark\Tests;

use Zhukmax\Waymark\Router;

/**
 * Class RouterTest
 * @package Zhukmax\Waymark\Tests
 */
class RouterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Router::get
     * @throws \ReflectionException
     */
    public function testGet()
    {
        self::assertClassHasAttribute('tplEngine', Router::class);

        $router = new Router([]);
        $router->get('/test', TestController::class, 'index');

        $property = Helper::getPrivateProperty(Router::class, 'routes');
        $routes = $property->getValue($router);
        self::assertIsArray($routes);
        self::assertCount(1, $routes);

        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertArrayHasKey('method', $routes[$key]);
        self::assertIsArray($routes[$key]['method']);
        self::assertCount(1, $routes[$key]['method']);
        self::assertEquals('get', $routes[$key]['method'][0]);
        self::assertArrayHasKey('action', $routes[$key]);
        self::assertArrayHasKey('type', $routes[$key]);
    }

    /**
     * @covers Router::all
     * @throws \ReflectionException
     */
    public function testAll()
    {
        self::assertClassHasAttribute('tplEngine', Router::class);

        $router = new Router([]);
        $router->all('/test', TestController::class, 'index');

        $property = Helper::getPrivateProperty(Router::class, 'routes');
        $routes = $property->getValue($router);
        self::assertIsArray($routes);
        self::assertCount(1, $routes);

        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertArrayHasKey('method', $routes[$key]);
        self::assertIsArray($routes[$key]['method']);
        self::assertCount(4, $routes[$key]['method']);
        self::assertArrayHasKey('action', $routes[$key]);
        self::assertArrayHasKey('type', $routes[$key]);
    }

    /**
     * @covers Router::post
     * @throws \ReflectionException
     */
    public function testPost()
    {
        self::assertClassHasAttribute('tplEngine', Router::class);

        $router = new Router([]);
        $router->post('/test', TestController::class, 'index');

        $property = Helper::getPrivateProperty(Router::class, 'routes');
        $routes = $property->getValue($router);
        self::assertIsArray($routes);
        self::assertCount(1, $routes);

        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertArrayHasKey('method', $routes[$key]);
        self::assertIsArray($routes[$key]['method']);
        self::assertCount(1, $routes[$key]['method']);
        self::assertEquals('post', $routes[$key]['method'][0]);
        self::assertArrayHasKey('action', $routes[$key]);
        self::assertArrayHasKey('type', $routes[$key]);
    }

    /**
     * @covers Router::put
     * @throws \ReflectionException
     */
    public function testPut()
    {
        self::assertClassHasAttribute('tplEngine', Router::class);

        $router = new Router([]);
        $router->put('/test', TestController::class, 'index');

        $property = Helper::getPrivateProperty(Router::class, 'routes');
        $routes = $property->getValue($router);
        self::assertIsArray($routes);
        self::assertCount(1, $routes);

        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertArrayHasKey('method', $routes[$key]);
        self::assertIsArray($routes[$key]['method']);
        self::assertCount(1, $routes[$key]['method']);
        self::assertEquals('put', $routes[$key]['method'][0]);
        self::assertArrayHasKey('action', $routes[$key]);
        self::assertArrayHasKey('type', $routes[$key]);
    }

    /**
     * @covers Router::delete
     * @throws \ReflectionException
     */
    public function testDelete()
    {
        self::assertClassHasAttribute('tplEngine', Router::class);

        $router = new Router([]);
        $router->delete('/test', TestController::class, 'index');

        $property = Helper::getPrivateProperty(Router::class, 'routes');
        $routes = $property->getValue($router);
        self::assertIsArray($routes);
        self::assertCount(1, $routes);

        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertArrayHasKey('method', $routes[$key]);
        self::assertIsArray($routes[$key]['method']);
        self::assertCount(1, $routes[$key]['method']);
        self::assertEquals('delete', $routes[$key]['method'][0]);
        self::assertArrayHasKey('action', $routes[$key]);
        self::assertArrayHasKey('type', $routes[$key]);
    }

    /**
     * @covers Router::executeRoute
     * @throws \ReflectionException
     */
    public function testExecuteRoute()
    {
        $router = new Router([]);
        $router->get('/test', TestController::class, 'test');

        $method = Helper::getPrivateMethod(Router::class, 'executeRoute');
        self::assertTrue($method->isProtected());
    }
}
