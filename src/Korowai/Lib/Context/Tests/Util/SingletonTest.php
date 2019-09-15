<?php
/**
 * @file src/Korowai/Lib/Context/Tests/Util/SingletonTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context\Tests\Util;

use Korowai\Lib\Context\Util\Singleton;

use PHPUnit\Framework\TestCase;

class SingletonC91F82BJ
{
    use Singleton;
};

class Singleton76YO7MV5
{
    use Singleton;

    public $value;

    protected function initializeSingleton()
    {
        $this->value = 'initialized';
    }
};

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SingletonTest extends TestCase
{
    protected function getPrivateErrorRegExp(string $method)
    {
        return '/Call to private (?:method )?' . preg_quote($method) . '/';
    }

    public function test__TrivialSingleton__getInstance()
    {
        $obj1 = SingletonC91F82BJ::getInstance();
        $obj2 = SingletonC91F82BJ::getInstance();
        $this->assertSame($obj1, $obj2);
    }

    public function test__TrivialSingleton__construct()
    {
        $regex = $this->getPrivateErrorRegExp(SingletonC91F82BJ::class . '::__construct()');
        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($regex);

        new SingletonC91F82BJ();
    }

    public function test__TrivialSingleton__clone()
    {
        $obj = SingletonC91F82BJ::getInstance();

        $regex = $this->getPrivateErrorRegExp(get_class($obj) . '::__clone()');

        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($regex);

        $obj->__clone();
    }

    public function test__TrivialSingleton__wakeup()
    {
        $obj = SingletonC91F82BJ::getInstance();

        $regex = $this->getPrivateErrorRegExp(get_class($obj) . '::__wakeup()');

        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($regex);

        $obj->__wakeup();
    }

    public function test__SingletonWithInitializer()
    {
        $obj = Singleton76YO7MV5::getInstance();
        $this->assertEquals('initialized', $obj->value);
    }
}

// vim: syntax=php sw=4 ts=4 et:
