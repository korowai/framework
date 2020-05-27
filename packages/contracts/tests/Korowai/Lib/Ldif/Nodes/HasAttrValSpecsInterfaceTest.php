<?php
/**
 * @file tests/Korowai/Lib/Ldif/AttrValSpecsInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class HasAttrValSpecsInterfaceTest extends TestCase
{
    public function test__dummyImplementation()
    {
        $dummy = new class implements HasAttrValSpecsInterface {
            use HasAttrValSpecsInterfaceTrait;
        };
        $this->assertImplementsInterface(HasAttrValSpecsInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'attrValSpecs'  => 'getAttrValSpecs'
        ];
        $this->assertObjectPropertyGetters($expect, HasAttrValSpecsInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
