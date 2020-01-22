# SimpleRouter
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](license.md)
[![Total Downloads][ico-downloads]][link-downloads]

Simple router for php7.1+ projects like API.

## Install
Using composer:
```console
$ composer require zhukmax/simple-router
```

## Using
If you need Template engine in your project you can use your favorite like I use Twig in example, but if you need only json/csv responces then just use SimpleRouter without any Template engine.
```php
<?php

require_once 'vendor/autoload.php';

use ProjectName\API\Controllers\IndexController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zhukmax\Router\Router;

/** Add Twig Template engine **/
$loader = new FilesystemLoader(__DIR__ . '/src/views');
$twig = new Environment($loader);

$router = new Router([
    'tplEngine' => $twig
]);
$router
    ->get('/api/users', IndexController::class, 'actionGetAll', 'json')
    ->output();
```
You can use Request static methods if you need $_GET/$_POST data in your action-method. The methods have basic data-filters for *intiger*, *email*.
```php
<?php

namespace ProjectName\API\Controllers;

use Zhukmax\Router\Request;

class IndexController
{
    public static function actionGetAll()
    {
        $date = Request::get('date');
        $page = Request::getInt('page', 0);
        
        return [
            'date' => $date,
            'page'=> $page
        ];
    }
}
```

## License

The Apache License Version 2.0. You can find text of License in the [License File](license.md).

[ico-version]: https://img.shields.io/packagist/v/zhukmax/simple-router.svg
[ico-license]: https://img.shields.io/badge/license-Apache%202-brightgreen.svg
[ico-downloads]: https://img.shields.io/packagist/dt/zhukmax/simple-router.svg

[link-packagist]: https://packagist.org/packages/zhukmax/simple-router
[link-downloads]: https://packagist.org/packages/zhukmax/simple-router
