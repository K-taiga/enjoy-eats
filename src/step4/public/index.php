<?php

declare(strict_types=1);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Formatter\LineFormatter;
use App\Libs\Core\Container;
use App\Libs\Mailer\SwiftMailSender;

require __DIR__ . '/../vendor/autoload.php';

/*
 * 以下は例外処理に関連する処理です。
 */
$handleException = function (Throwable $e) {
    (new App\Modules\Common\Controllers\ExceptionController())->showAction($e);
};
set_exception_handler($handleException);

/*
 * 以下は、DIコンテナに関連する処理です。
 */
$container = Container::getInstance();
$container->set('mailer', function ($c) {
    $mailer = new SwiftMailSender();
    return $mailer;
});
$container->set('logger', function ($c) {
    // ロガー名「MyApplication」を初期化します。
    $logger = new Logger('MyApplication');
    // exception.logにエラー情報を記録するためにStreamHandlerを追加します。
    $logger->pushHandler(
        (new StreamHandler(__DIR__ . '/../logs/exceptions.log', Logger::ERROR))
            ->setFormatter(new LineFormatter(null, null, true))
            ->pushProcessor(new IntrospectionProcessor())
    );
    return $logger;
});

/*
 * 以下は、ルーティングに関連する処理です。
 */
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) {
    $router->addRoute(['GET'], '/', function($params) {
        $controller = new App\Controllers\IndexController();
        $controller->indexAction();
    });
    $router->addRoute(['GET'], '/user', function($params) {
        $controller = new App\Controllers\UserController();
        $controller->indexAction();
    });
    $router->addRoute(['GET'], '/user/{user-id}', function($params) {
        $controller = new App\Controllers\UserController();
        $controller->showAction($params);
    });
});

// GETパラメータを除去する。
// たとえば、$uriが「/user?id=777」だったとき、「/user」のように変換する。
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // エラーはset_exception_handlerに渡される
        throw new \App\Libs\Core\Exception\PageNotFoundException();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        throw new \App\Libs\Core\Exception\MethodNotAllowedException();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $params = $routeInfo[2];
        echo $handler($params);
        break;
}
