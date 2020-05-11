<?php
/**
 * @file tests/TestCaseTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Lib\Ldif;

use Korowai\Testing\TestCase as BaseTestCase;
use Korowai\Testing\Lib\Ldif\TestCase;
use Korowai\Testing\Lib\Ldif\Traits\ObjectProperties;
use Korowai\Testing\Lib\Ldif\Traits\ParserTestHelpers;
use Korowai\Testing\Lib\Ldif\Assertions\ObjectPropertiesAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class TestCaseTest extends TestCase
{
    public function test__extends__TestCase()
    {
        $this->assertExtendsClass(BaseTestCase::class, parent::class);
    }

    public function test__uses__ObjectProperties()
    {
        $this->assertUsesTrait(ObjectProperties::class, parent::class);
    }

    public function test__uses__ObjectPropertiesAssertions()
    {
        $this->assertUsesTrait(ObjectPropertiesAssertions::class, parent::class);
    }

    public function test__uses__ParserTestHelpers()
    {
        $this->assertUsesTrait(ParserTestHelpers::class, parent::class);
    }

    public function test__objectPropertyGettersMap()
    {
        $this->assertSame(parent::$ldiflibObjectPropertyGettersMap, parent::objectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et:
