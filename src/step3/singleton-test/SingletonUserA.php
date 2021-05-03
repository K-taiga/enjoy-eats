<?php

require_once __DIR__ . '/SingletonSample.php';
class SingletonUserA {
    public function setGreeting() {
        $singleton           = SingletonSample::getInstance();
        $singleton->greeting = 'Hello';
    }
}