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
```php
<?php

require_once 'vendor/autoload.php';

use ProjectName\API\Controllers\IndexController;
use Zhukmax\Router\Router;

$router = new Router();
$router
    ->get('/api/users', IndexController::class, 'actionGetAll', 'json')
    ->output();
```

```php
<?php

namespace ProjectName\API\Controllers;

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
