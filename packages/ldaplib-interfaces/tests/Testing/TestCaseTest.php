<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Testing;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\LdaplibInterfaces\TestCase
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
            \Korowai\Testing\LdaplibInterfaces\ObjectPropertyGettersMap::getObjectPropertyGettersMap()
        );
        $this->assertSame($expected, parent::objectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et: