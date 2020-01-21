<?php
/**
 * @file Tests/Traits/WrapsCoupledLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\WrapsCoupledLocation;
use Korowai\Lib\Ldif\Traits\ExposesCoupledLocation;
use Korowai\Lib\Ldif\CoupledLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class WrapsCoupledLocationTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use WrapsCoupledLocation; };
    }

    public function test__uses__ExposesCoupledLocation()
    {
        $uses = class_uses(WrapsCoupledLocation::class);
        $this->assertContains(ExposesCoupledLocation::class, $uses);
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
