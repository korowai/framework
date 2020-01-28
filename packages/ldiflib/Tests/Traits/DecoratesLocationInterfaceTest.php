<?php
/**
 * @file Tests/Traits/DecoratesLocationInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\DecoratesLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Lib\Ldif\LocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesLocationInterfaceTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use DecoratesLocationInterface; };
    }

    public function test__uses__ExposesLocationInterface()
    {
        $uses = class_uses(DecoratesLocationInterface::class);
        $this->assertContains(ExposesLocationInterface::class, $uses);
    }

    public function test__coupledLocation()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();

        $obj = $this->getTestObject();
        $this->assertNull($obj->getLocation());

        $this->assertSame($obj, $obj->setLocation($location));
        $this->assertSame($location, $obj->getLocation());
    }
}

// vim: syntax=php sw=4 ts=4 et: