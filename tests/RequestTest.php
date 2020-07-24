<?php

namespace Zhukmax\Waymark\Tests;

use Zhukmax\Waymark\Request;

/**
 * Class RequestTest
 * @package Zhukmax\Waymark\Tests
 */
class RequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Request::get
     */
    public function testGet()
    {
        self::assertEmpty(Request::get('getVar'));
        self::assertNull(Request::get('getVar'));

        $_REQUEST['getVar'] = 'test';
        self::assertEquals('test', Request::get('getVar'));

        $_REQUEST['getVar'] = 'cr"ew';
        self::assertEquals('cr\"ew', Request::get('getVar'));

        $_REQUEST = [];
    }

    /**
     * @covers Request::getEmail
     */
    public function testGetEmail()
    {
        self::assertEquals('', Request::getEmail('emailVar'));

        $_REQUEST['emailVar'] = 'test';
        self::assertEquals('', Request::getEmail('emailVar'));

        $_REQUEST['emailVar'] = 'test@gmail.com';
        self::assertEquals('test@gmail.com', Request::getEmail('emailVar'));

        $_REQUEST['emailVar'] = "test@gmail.com'insert";
        self::assertEquals('', Request::getEmail('emailVar'));

        $_REQUEST['email'] = 'test2@gmail.com';
        self::assertEquals('test2@gmail.com', Request::getEmail());

        $_REQUEST = [];
    }

    /**
     * @covers Request::getImages
     * @depends testGetFiles
     */
    public function testGetImages()
    {
        self::assertIsArray(Request::getImages());

        $_FILES[] = [
            'name' => 'test',
            'size' => 1024,
            'type' => 'video/x-msvideo',
            'tmp_name' => 'test.png'
        ];

        self::assertCount(0, Request::getImages());

        $_FILES[] = [
            'name' => 'test',
            'size' => 1024,
            'type' => 'image/png',
            'tmp_name' => 'test.png'
        ];

        self::assertArrayHasKey('name', Request::getImages()[0]);
        self::assertArrayHasKey('size', Request::getImages()[0]);
        self::assertArrayHasKey('type', Request::getImages()[0]);
        self::assertArrayHasKey('tmp_name', Request::getImages()[0]);

        $_FILES = [];

        self::assertCount(0, Request::getImages());
    }

    /**
     * @covers Request::getInt
     */
    public function testGetInt()
    {
        self::assertEquals(0, Request::getInt('intVar'));
        self::assertEquals(23, Request::getInt('intVar', null, null, 23));

        $_REQUEST['intVar'] = 'hello';
        self::assertEquals(0, Request::getInt('intVar'));

        $_REQUEST['intVar'] = 'hello2';
        self::assertEquals(0, Request::getInt('intVar'));

        $_REQUEST['intVar'] = 123;
        self::assertEquals(123, Request::getInt('intVar'));
        self::assertEquals(0, Request::getInt('intVar', 200));
        self::assertEquals(123, Request::getInt('intVar', 100,200));
        self::assertEquals(101, Request::getInt('intVar', 100,110, 101));
        self::assertNotEquals(123, Request::getInt('intVar', 10,100));

        $_REQUEST = [];
    }

    /**
     * @covers Request::getArray
     */
    public function testGetArray()
    {
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(0, Request::getArray('arrayVar'));

        $_REQUEST['arrayVar'] = [1, 2, 3];
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(3, Request::getArray('arrayVar'));

        $_REQUEST['arrayVar'] = json_encode([1, 2, 3]);
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(3, Request::getArray('arrayVar'));

        $_REQUEST['arrayVar'] = "1,2,3";
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(3, Request::getArray('arrayVar'));

        $_REQUEST['arrayVar'] = "123";
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(0, Request::getArray('arrayVar'));

        $_REQUEST['arrayVar'] = 123;
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(0, Request::getArray('arrayVar'));

        $_REQUEST['arrayVar'] = true;
        self::assertIsArray(Request::getArray('arrayVar'));
        self::assertCount(0, Request::getArray('arrayVar'));

        $_REQUEST = [];
    }

    /**
     * @covers Request::getFiles
     */
    public function testGetFiles()
    {
        self::assertIsArray(Request::getFiles());

        $_FILES[] = [
            'name' => 'test',
            'size' => 1024,
            'type' => 'application/vnd.amazon.ebook',
            'tmp_name' => 'test.png'
        ];

        $first = Request::getFiles()[0];
        self::assertArrayHasKey('name', $first);
        self::assertArrayHasKey('size', $first);
        self::assertArrayHasKey('type', $first);
        self::assertArrayHasKey('tmp_name', $first);

        $_FILES = [];

        self::assertCount(0, Request::getFiles());
    }

    /**
     * @covers Request::getArgs
     * @throws \ReflectionException
     */
    public function testGetArgs()
    {
        $index = Request::getArgs(TestController::class, 'index');
        self::assertIsArray($index);
        self::assertCount(0, $index);

        $_REQUEST['page'] = 24;

        $test = Request::getArgs(TestController::class, 'test');
        self::assertIsArray($test);
        self::assertCount(2, $test);
        self::assertArrayHasKey('page', $test);
        self::assertEquals(24, $test['page']);
        self::assertArrayHasKey('title', $test);
        self::assertEquals('Test title', $test['title']);

        $_REQUEST['title'] = 'Hello world';
        self::assertEquals('Hello world',
            Request::getArgs(TestController::class, 'test')['title']);

        $_REQUEST = [];
    }

    /**
     * @covers Request::getArgs
     * @depends testGetArgs
     * @throws \ReflectionException
     */
    public function testGetArgsNoClass()
    {
        $this->expectException(\ReflectionException::class);
        Request::getArgs('TestController', 'something');
    }

    /**
     * @covers Request::getArgs
     * @depends testGetArgsNoClass
     * @throws \ReflectionException
     */
    public function testGetArgsNoAction()
    {
        $this->expectException(\ReflectionException::class);
        Request::getArgs(TestController::class, 'something');
    }

    /**
     * @covers Request::getBool
     */
    public function testGetBool()
    {
        self::assertTrue(Request::getBool('boolVar', true));
        self::assertFalse(Request::getBool('boolVar'));

        $_REQUEST['boolVar'] = true;
        self::assertTrue(Request::getBool('boolVar'));
    }
}
