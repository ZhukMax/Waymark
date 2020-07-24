<?php

namespace Zhukmax\Waymark\Tests;

use Zhukmax\Waymark\AbstractController;

/**
 * Class TestController
 * @package Zhukmax\Waymark\Tests
 */
class TestController extends AbstractController
{
    public function index()
    {}

    public function test(int $page, string $title = "Test title")
    {
        return [
            'page'=> $page,
            'title' => $title
        ];
    }
}
