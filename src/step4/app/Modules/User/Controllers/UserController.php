<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\Core\Exception\InternalServerErrorException;

/**
 * ユーザに関連する画面コントローラー
 * @package App\Controllers
 */
class UserController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
    }

    /**
     * ユーザ一覧画面のコントロール処理を行う
     */
    public function indexAction(): void
    {
        throw new InternalServerErrorException('内部エラーが起こりました');
    }

    /**
     * ユーザ詳細画面のコントロール処理を行う
     */
    public function showAction($params): void
    {
        echo 'UserController::showAction()がコールされました。';
        echo '指定されたユーザIDは：', $params['user-id'];
    }

}