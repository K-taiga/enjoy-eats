<?php
require_once __DIR__ . '/SingletonSample.php';
class SingletonUserB {
    public function getGreeting() {
        $singleton = SingletonSample::getInstance();
        return $singleton->greeting;
    }
}