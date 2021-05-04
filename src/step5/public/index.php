<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// クロージャーを受け取り直後にその関数を実行する
$handleException = require __DIR__ . '/../app/Core/handle-exception.php';
$handleException();

$container = require __DIR__ . '/../app/Core/container.php';
$container();

$routes = require __DIR__ . '/../app/Core/routes.php';
$routes();
