<?php
/**
 * @file tests/Traits/HasAttrValSpecsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Lib\Ldif\Records\AttrValSpecsInterface;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class HasAttrValSpecsTest extends TestCase
{
    public function getTestObject()
    {
        return new class implements AttrValSpecsInterface { use HasAttrValSpecs; };
    }

    public function test__attrValSpecs()
    {
        $object = $this->getTestObject();
        $this->assertSame($object, $object->setAttrValSpecs(['A']));
        $this->assertSame(['A'], $object->getAttrValSpecs());
    }
}

// vim: syntax=php sw=4 ts=4 et: