<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * Mariadbデータベース用の汎用的なリポジトリクラス
 * @package App\Libs\Core
 */
abstract class AbstractMariadbRepository extends AbstractSqlRepository
{
    /**
     * コンストラクタ
     */
    public function __construct(string $tableName)
    {
        parent::__construct($tableName);
    }

    /**
     * 直近にinsertしたIDカラム値(またはシーケンス値)を取得する
     * DBごとに書き方が異なるためこのクラスに記述
     * @param string|null $sequenceName シーケンス名
     * @return int|null シーケンス値
     */
    protected function lastInsertId(?string $sequenceName = null): ?int
    {
        return (int)$this->connection->lastInsertId();
    }
}