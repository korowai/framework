<?php
/**
 * @file Tests/Traits/DecoratesCoupledRangeInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\DecoratesCoupledRangeInterface;
use Korowai\Lib\Ldif\Traits\ExposesCoupledRangeInterface;
use Korowai\Lib\Ldif\CoupledRangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesCoupledRangeInterfaceTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use DecoratesCoupledRangeInterface; };
    }

    public function test__uses__ExposesCoupledRangeInterface()
    {
        $uses = class_uses(DecoratesCoupledRangeInterface::class);
        $this->assertContains(ExposesCoupledRangeInterface::class, $uses);
    }

    public function test__coupledRange()
    {
        $location = $this->getMockBuilder(CoupledRangeInterface::class)
                         ->getMockForAbstractClass();

        $obj = $this->getTestObject();
        $this->assertNull($obj->getRange());

        $this->assertSame($obj, $obj->setRange($location));
        $this->assertSame($location, $obj->getRange());
    }
}

// vim: syntax=php sw=4 ts=4 et:
