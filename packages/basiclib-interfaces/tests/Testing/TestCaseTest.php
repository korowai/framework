<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\BasiclibInterfaces;

use Korowai\Testing\BasiclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\BasiclibInterfaces\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function test__extends__TestCase()
    {
        $this->assertExtendsClass(\Korowai\Testing\TestCase::class, parent::class);
    }

    public function test__objectPropertyGettersMap()
    {
        $expected = array_merge_recursive(
            \Korowai\Testing\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\BasiclibInterfaces\ObjectPropertyGettersMap::getObjectPropertyGettersMap()
        );
        $this->assertSame($expected, parent::objectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et:
