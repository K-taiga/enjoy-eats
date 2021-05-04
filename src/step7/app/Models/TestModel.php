<?php

declare(strict_types=1);

namespace App\Models;

use App\Libs\Core\Container;
use App\Repositories\TestRepositoryInterface;

/**
 * テスト用のモデルクラス
 * @package App\Models
 */
class TestModel
{
    /**
     * TestRepositoryInterfaceを実装するインスタンス
     * Interfaceとしてプロパティを持つことでTestMariadbRepository以外もセットできるようになる
     */
    private TestRepositoryInterface $testRepository;

    /**
     * TestModel constructor.
     */
    public function __construct()
    {
        // コンストラクタでDIコンテナからTestRepositoryを取得
        // DIコンテナにセットしてるのはApp/Core/container.phpのline52
        $this->testRepository = Container::getInstance()->get('TestRepository');
    }

    /**
     * PDOの動作確認のためのデータ生成メソッド
     */
    public function create()
    {
        return $this->testRepository->create(['value1' => 'step7']);
    }
}