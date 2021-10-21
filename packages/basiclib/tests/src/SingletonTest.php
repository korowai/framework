<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Basic;

use Korowai\Lib\Basic\Singleton;
use Korowai\Testing\Basiclib\SingletonTestTrait;
use PHPUnit\Framework\TestCase;

class SingletonC91F82BJ
{
    use Singleton;
}

class Singleton76YO7MV5
{
    use Singleton;

    public $value;

    protected function initializeSingleton()
    {
        $this->value = 'initialized';
    }
}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Basic\Singleton
 *
 * @internal
 */
final class SingletonTest extends TestCase
{
    use SingletonTestTrait;

    public function testInitializeSingleton(): void
    {
        $obj = Singleton76YO7MV5::getInstance();
        $this->assertEquals('initialized', $obj->value);
    }

    protected function getSingletonClassUnderTest()
    {
        return SingletonC91F82BJ::class;
    }
}

// vim: syntax=php sw=4 ts=4 et:
