<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\Core\Container;
use App\Libs\Mailer\MailerInterface;

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

    // DIコンテナから取り出す方は具体的なクラス名ではなくInterface名として取り出すことで、他のクラスへの差し替えが可能になる
    private MailerInterface $mailer;

    /**
     * ユーザ一覧画面のコントロール処理を行う
     */
    public function indexAction(): void
    {
        echo 'UserController::indexAction()がコールされました。';
        $mailer = Container::getInstance()->get('mailer');
        var_dump($mailer);
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