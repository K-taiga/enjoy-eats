<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// FastRouteのdispathcerのインスタンスを返す
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) {
    // パスがなくGETメソッドの場合 IndexController
    $router->addRoute(['GET'], '/', function($params) {
        $controller = new App\Controllers\IndexController();
        $controller->indexAction();
    });
    // パスがuserでGET UserController の index
    $router->addRoute(['GET'], '/user', function($params) {
        $controller = new App\Controllers\UserController();
        $controller->indexAction();
    });
    // パスがuser/{user-id} UserController の show
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
// URLエンコードされたのをデコード
$uri = rawurldecode($uri);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
// $routeInfoの結果ごとにswitch
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'ページがみつかりません。';
        break;
    // HTTP_METHODと一致しない
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo 'HTTPメソッドが許可されていません。';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $params = $routeInfo[2];
        echo $handler($params);
        break;
}