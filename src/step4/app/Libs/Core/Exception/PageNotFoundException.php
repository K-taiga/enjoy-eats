<?php

declare(strict_types=1);

namespace App\Libs\Core\Exception;

/**
 * ページが見つからなかったことを表す例外クラス
 * @package App\Libs\Core\Exception
 */
// phpにあらかじめ用意されているExceptionクラスを使うため\Exceptionとする
// namespace App\Libs\Core\Exception配下のExceptionクラスではない
// もしくはphpのクラスを使う場合はuse Exeptionとして\はなくしてもOK
class PageNotFoundException extends \Exception implements HttpExceptionInterface
{
    /**
     * HTTPステータスコードを返す
     */
    public function getHttpStatusCode(): array
    {
        return [404, 'Not Found'];
    }
}