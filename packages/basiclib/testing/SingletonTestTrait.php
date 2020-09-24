<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Basiclib;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SingletonTestTrait
{
    abstract public static function getSingletonClassUnderTest(): string;

    public function test__Singleton__getInstance()
    {
        $class = static::getSingletonClassUnderTest();

        $obj1 = $class::getInstance();
        $obj2 = $class::getInstance();
        $this->assertSame($obj1, $obj2);
    }

    public function test__Singleton__construct(): void
    {
        $class = static::getSingletonClassUnderTest();

        $regex = self::getPrivateErrorRegExp($class.'::__construct()');
        $this->expectException(\Error::class);
        $this->expectExceptionMessageMatches($regex);

        new $class();
    }

    public function test__Singleton__clone(): void
    {
        $class = static::getSingletonClassUnderTest();

        $obj = $class::getInstance();

        $regex = self::getPrivateErrorRegExp(get_class($obj).'::__clone()');

        $this->expectException(\Error::class);
        $this->expectExceptionMessageMatches($regex);

        $obj->__clone();
    }

    public function test__Singleton__wakeup(): void
    {
        $class = static::getSingletonClassUnderTest();

        $obj = $class::getInstance();

        $regex = self::getPrivateErrorRegExp(get_class($obj).'::__wakeup()');

        $this->expectException(\Error::class);
        $this->expectExceptionMessageMatches($regex);

        $obj->__wakeup();
    }

    protected static function getPrivateErrorRegExp(string $method)
    {
        return '/Call to private (?:method )?'.preg_quote($method).'/';
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
