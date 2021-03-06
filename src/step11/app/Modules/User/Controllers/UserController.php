<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;
use App\Libs\Core\Exception\ForbiddenException;
use App\Models\UserModel;

/**
 * ユーザに関連する画面コントローラー
 * @package App\Controllers
 */
class UserController extends AbstractUserController
{
    /**
     * @var UserModel ユーザモデルクラスのインスタンス
     */
    private UserModel $userModel;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * ユーザ登録の開始ページ(メールアドレス入力ページ)に関連するコントロール処理を行う
     */
    public function startAction(): void
    {
        $this->view->layoutTitle = 'ユーザ登録';
        if ($this->request->hasPost('send')) {
            $this->checkCsrfToken();
            $errors = $this->userModel->validateOnStart($this->request->getAllPost());
            if (count($errors) > 0) {
                $this->view->errors = $errors;
            } else {
                // 確認コードをメール送信
                $authCode = $this->userModel->sendAuthCode($this->request->getAllPost());
                // 確認コードとメールアドレスをsessionにセット
                // 次のページ(/user/input-token)で使う
                $this->session->set('auth-code', $authCode);
                $this->session->set('mail', $this->request->byPost('mail'));
                $this->forwardTo(303, '/user/input-token');
                return;
            }
        }
        // 初期表示時 or エラーが有った場合にCSRFトークンを生成
        $this->view->csrfToken = $this->session->generateCsrfToken();
        echo $this->view->render();
    }

    /**
     * ユーザ登録の確認コード入力ページに関連するコントロール処理を行う
     */
    public function inputTokenAction(): void
    {
        $this->view->layoutTitle = 'ユーザ登録';
        // sessionのkeyチェック
        if (is_null($this->session->get('auth-code'))) {
            throw new ForbiddenException();
        }
        // 
        if ($this->request->hasPost('send')) {
            // requestのauth-codeとsessionのauth-codeが合ってるかチェック
            if ($this->userModel->isValidToken($this->request->byPost('auth-code'), $this->session->get('auth-code')) !== true) {
                    $this->view->errors = ['確認コードが一致しません'];
            } else {
                $this->session->set('hasValidToken', true);
                $this->forwardTo(303, '/user/create');
            }
        }
        echo $this->view->render();
    }

    /**
     * ユーザ登録の本登録ページ(ハンドルネームの入力ページ)に関連するコントロール処理を行う
     */
    public function createAction(): void
    {
        $this->view->layoutTitle = 'ユーザ登録';
        // hasValidTokenのflagのチェック
        if (is_null($this->session->get('auth-code')) || $this->session->get('hasValidToken') !== true) {
            throw new ForbiddenException();
        }
        if ($this->request->hasPost('send')) {
            $this->checkCsrfToken();
            $input = $this->request->getAllPost();
            $input['mail'] = $this->session->get('mail');
            $errors = $this->userModel->validateOnCreate($input);
            if (count($errors) > 0) {
                $this->view->errors = $errors;
                // エラーがあればnameは残す
                $this->view->name = $this->request->byPost('name');
            } else {
                // userテーブルにinsert
                $this->userModel->create($input);
                // session削除
                $this->session->clear();
                $this->forwardTo(303, '/user/created');
                return;
            }
        }
        $this->view->csrfToken = $this->session->generateCsrfToken();
        echo $this->view->render();
    }

    /**
     * ユーザ登録の完了ページに関連するコントロール処理を行う
     */
    public function createdAction(): void
    {
        $this->view->layoutTitle = 'ユーザ登録';
        echo $this->view->render();
    }
}