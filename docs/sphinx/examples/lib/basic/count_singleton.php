<?php

use Korowai\Lib\Basic\SingletonInterface;
use Korowai\Lib\Basic\Singleton;

class CountSingleton implements SingletonInterface
{
    use Singleton;

    static public $count = 0;

    public function initializeSingleton()
    {
        self::$count += 1;
        print("initializeSingleton(): count: ".self::$count."\n");
    }
}

print("main 1: count: ".CountSingleton::$count."\n");
$var1 = CountSingleton::getInstance();
print("main 2: count: ".CountSingleton::$count."\n");
$var2 = CountSingleton::getInstance();
print("main 3: count: ".CountSingleton::$count."\n");
print("main 4: (var1 === var2): ".($var1 === $var2 ? 'true' : 'false')."\n");

// vim: syntax=php sw=4 ts=4 et:
