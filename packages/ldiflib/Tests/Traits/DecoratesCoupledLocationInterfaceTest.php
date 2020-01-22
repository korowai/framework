<?php
/**
 * @file Tests/Traits/DecoratesCoupledLocationInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\DecoratesCoupledLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesCoupledLocationInterface;
use Korowai\Lib\Ldif\CoupledLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesCoupledLocationInterfaceTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use DecoratesCoupledLocationInterface; };
    }

    public function test__uses__ExposesCoupledLocationInterface()
    {
        $uses = class_uses(DecoratesCoupledLocationInterface::class);
        $this->assertContains(ExposesCoupledLocationInterface::class, $uses);
    }

    public function test__coupledLocation()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();

        $obj = $this->getTestObject();
        $this->assertNull($obj->getLocation());

        $this->assertSame($obj, $obj->setLocation($location));
        $this->assertSame($location, $obj->getLocation());
    }
}

// vim: syntax=php sw=4 ts=4 et:
