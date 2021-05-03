<?php
require_once __DIR__ . '/SingletonUserA.php';
require_once __DIR__ . '/SingletonUserB.php';
$userA = new SingletonUserA();
$userA->setGreeting();
$userB = new SingletonUserB();
echo $userB->getGreeting(), PHP_EOL;