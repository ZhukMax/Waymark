<?php

namespace Zhukmax\Waymark\Tests;

use Zhukmax\Waymark\Exception;
use Zhukmax\Waymark\Interfaces\TplEngineInterface;
use Zhukmax\Waymark\Router;

/**
 * Class RouterTest
 * @package Zhukmax\Waymark\Tests
 */
class RouterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Router::__construct
     */
    public function testConstructorTpl()
    {
        self::assertClassHasAttribute('tplEngine', Router::class);
        $tplEngine = new class implements TplEngineInterface {
            /**
             * @param string $name
             * @param array $context
             * @return string
             */
            public function render(string $name, array $context)
            {
                return $name;
            }
        };

        $router = new Router([
            'tplEngine' => $tplEngine
        ]);

        $renderResult = $router->tplEngine->render('test', []);
        self::assertEquals('test', $renderResult);
    }

    /**
     * @covers Router::__construct
     * @throws \ReflectionException
     */
    public function testConstructorNamespace()
    {
        $router = new Router([
            'namespace' => '\\Zhukmax\\Tests'
        ]);

        $property = Helper::getPrivateProperty(Router::class, 'namespace');
        $namespace = $property->getValue($router);
        self::assertEquals('\\Zhukmax\\Tests', $namespace);

        $router2 = new Router([]);
        $namespace2 = $property->getValue($router2);
        self::assertEmpty($namespace2);
    }

    /**
     * @covers Router::__construct
     * @throws \ReflectionException
     */
    public function testConstructorRoutesFromFile()
    {
        $router = new Router([
            'namespace' => '\\Zhukmax\\Waymark\\Tests',
            'routes' => dirname(__FILE__) . '/routes.json'
        ]);
        $this->routeCheck($router, 'get', 'html');
    }

    /**
     * @covers Router::__construct
     * @throws \ReflectionException
     */
    public function testConstructorRoutesFromArray()
    {
        $router = new Router([
            'namespace' => '\\Zhukmax\\Waymark\\Tests',
            'routes' => [
                'get' => [
                    '/index.html' => [
                        'test', 'index', 'csv'
                    ]
                ]
            ]
        ]);
        $this->routeCheck($router, 'get', 'csv');
    }

    /**
     * @covers Router::routesFromArray
     * @throws \ReflectionException
     */
    public function testRoutesFromArrayExceptionMethod()
    {
        $routes = [
            'strange' => [
                '/index.html' => [
                    'test', 'index', 'json'
                ]
            ]
        ];
        $method = Helper::getPrivateMethod(Router::class, 'routesFromArray');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not exist method: strange');
        $method->invokeArgs(new Router(['namespace' => '\\Zhukmax\\Waymark\\Tests']), [$routes]);
    }

    /**
     * @covers Router::routesFromArray
     * @throws \ReflectionException
     */
    public function testRoutesFromArrayExceptionController()
    {
        $routes = [
            'get' => [
                '/index.html' => [
                    'test', 'index', 'json'
                ]
            ]
        ];
        $method = Helper::getPrivateMethod(Router::class, 'routesFromArray');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Controller is not exists');
        $method->invokeArgs(new Router([]), [$routes]);
    }

    /**
     * @covers Router::get
     * @throws \ReflectionException
     */
    public function testGet()
    {
        $router = new Router([]);
        $router->get('/test', TestController::class, 'index');
        $this->routeCheck($router, 'get', 'json');
    }

    /**
     * @covers Router::all
     * @throws \ReflectionException
     */
    public function testAll()
    {
        $router = new Router([]);
        $router->all('/test', TestController::class, 'index');
        $routes = $this->getRoutes($router);

        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertClassHasAttribute('tpl', $routes[$key]['class']);
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
        $router = new Router([]);
        $router->post('/test', TestController::class, 'index');
        $this->routeCheck($router, 'post', 'json');
    }

    /**
     * @covers Router::put
     * @throws \ReflectionException
     */
    public function testPut()
    {
        $router = new Router([]);
        $router->put('/test', TestController::class, 'index');
        $this->routeCheck($router, 'put', 'json');
    }

    /**
     * @covers Router::delete
     * @throws \ReflectionException
     */
    public function testDelete()
    {
        $router = new Router([]);
        $router->delete('/test', TestController::class, 'index');
        $this->routeCheck($router, 'delete', 'json');
    }

    /**
     * @covers Router::executeRoute
     * @throws \ReflectionException
     */
    public function testExecuteRouteUrlException()
    {
        $router = new Router([]);
        $router->get('/test', TestController::class, 'test');

        $method = Helper::getPrivateMethod(Router::class, 'executeRoute');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Wrong url: hasn\'t equal route');
        $method->invoke($router);
    }

    /**
     * @covers Router::executeRoute
     * @depends testExecuteRouteUrlException
     * @throws \ReflectionException
     */
    public function testExecuteRouteHttpException()
    {
        $router = new Router([]);
        $router->get('/test/{str}/{int}', TestController::class, 'test');

        $method = Helper::getPrivateMethod(Router::class, 'executeRoute');

        $_SERVER["REQUEST_URI"] = '/test/max/1';
        $_SERVER["REQUEST_METHOD"] = 'POST';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Wrong HTTP method: POST');
        $method->invoke($router);
    }

    /**
     * @covers Router::executeRoute
     * @depends testExecuteRouteUrlException
     * @throws \ReflectionException
     */
    public function testExecuteRoute()
    {
        $router = new Router([]);
        $router->get('/test', TestController::class, 'test');

        $method = Helper::getPrivateMethod(Router::class, 'executeRoute');
        self::assertTrue($method->isProtected());

        $_SERVER["REQUEST_URI"] = '/test';
        $_SERVER["REQUEST_METHOD"] = 'GET';
        $method->invoke($router);

        $property = Helper::getPrivateProperty(Router::class, 'output');
        $output = $property->getValue($router);
        self::assertIsArray($output);
        self::assertArrayHasKey('title', $output);
        self::assertEquals(0, $output['page']);
    }

    /**
     * @param Router $router
     * @param string $type
     * @param string $method
     * @throws \ReflectionException
     */
    private function routeCheck(Router $router, string $method, string $type = 'html')
    {
        $routes = $this->getRoutes($router);
        $key = array_key_first($routes);
        self::assertArrayHasKey('class', $routes[$key]);
        self::assertClassHasAttribute('tpl', $routes[$key]['class']);
        self::assertArrayHasKey('method', $routes[$key]);
        self::assertIsArray($routes[$key]['method']);
        self::assertCount(1, $routes[$key]['method']);
        self::assertEquals($method, $routes[$key]['method'][0]);
        self::assertArrayHasKey('action', $routes[$key]);
        self::assertArrayHasKey('type', $routes[$key]);
        self::assertEquals($type, $routes[$key]['type']);
    }

    /**
     * @param Router $router
     * @return mixed
     * @throws \ReflectionException
     */
    private function getRoutes(Router $router)
    {
        $property = Helper::getPrivateProperty(Router::class, 'routes');
        $routes = $property->getValue($router);
        self::assertIsArray($routes);
        self::assertCount(1, $routes);

        return $routes;
    }
}
