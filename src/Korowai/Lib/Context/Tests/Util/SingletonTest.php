<?php
/**
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

class TrivialSingleton
{
    use Singleton;
};

class SingletonWithInitializer
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
 * @coversDefaultClass \Korowai\Lib\Context\Util\Singleton
 */
class SingletonTest extends TestCase
{
    protected function getPrivateErrorRegExp(string $method)
    {
        return '/Call to private (?:method )?' . preg_quote($method) . '/';
    }

    /**
     * @covers ::getInstance
     * @covers ::initializeSingleton
     */
    public function test__TrivialSingleton__getInstance()
    {
        $obj1 = TrivialSingleton::getInstance();
        $obj2 = TrivialSingleton::getInstance();
        $this->assertSame($obj1, $obj2);
    }

    /**
     * @covers ::__construct
     */
    public function test__TrivialSingleton__construct()
    {
        $regex = $this->getPrivateErrorRegExp(TrivialSingleton::class . '::__construct()');
        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($regex);

        new TrivialSingleton();
    }

    /**
     * @covers ::__clone
     */
    public function test__TrivialSingleton__clone()
    {
        $obj = TrivialSingleton::getInstance();

        $regex = $this->getPrivateErrorRegExp(get_class($obj) . '::__clone()');

        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($regex);

        $obj->__clone();
    }

    /**
     * @covers ::__wakeup
     */
    public function test__TrivialSingleton__wakeup()
    {
        $obj = TrivialSingleton::getInstance();

        $regex = $this->getPrivateErrorRegExp(get_class($obj) . '::__wakeup()');

        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($regex);

        $obj->__wakeup();
    }

    /**
     * @covers ::getInstance
     * @covers ::initializeSingleton
     */
    public function test__SingletonWithInitializer()
    {
        $obj = SingletonWithInitializer::getInstance();
        $this->assertEquals('initialized', $obj->value);
    }
}

// vim: syntax=php sw=4 ts=4 et:
