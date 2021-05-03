<?php
class SingletonSample {
    // 自分自身のインスタンスを静的プロパティ化(全体で一つだけのインスタンスとなる)
    private static $instance;
    public $greeting;
    // 外部のクラスから呼び出すためのgetter
    final public static function getInstance(): object {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    // final   = SingletonSampleクラスをオーバーライドできない
    // private = 外部のクラスからnewできない
    final private function __construct() {
        
    }
}