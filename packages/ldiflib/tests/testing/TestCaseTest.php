<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Ldiflib;

use Korowai\Testing\TestCase as BaseTestCase;
use Korowai\Testing\Ldiflib\TestCase;
use Korowai\Testing\Ldiflib\Traits\ParserTestHelpers;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Ldiflib\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function test__extends__TestCase() : void
    {
        $this->assertExtendsClass(BaseTestCase::class, parent::class);
    }

    public function test__uses__ParserTestHelpers() : void
    {
        $this->assertUsesTrait(ParserTestHelpers::class, parent::class);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expected = array_merge_recursive(
            \Korowai\Testing\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\RfclibInterfaces\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\LdiflibInterfaces\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\Rfclib\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\Ldiflib\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
        );
        $this->assertSame($expected, parent::objectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: