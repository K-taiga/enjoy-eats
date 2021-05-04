<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Libs\AbstractMariadbRepository;

/**
 * テスト用のリポジトリクラス
 * @package App\Repositories
 */
class TestMariadbRepository extends AbstractMariadbRepository implements TestRepositoryInterface
{
    /**
     * TestMariadbRepository constructor.
     */
    public function __construct()
    {
        // 親クラスのconstructを呼び出し、table名のtestsを引数に渡す
        parent::__construct('tests');
    }
}