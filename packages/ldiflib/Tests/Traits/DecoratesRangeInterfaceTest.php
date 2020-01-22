<?php
/**
 * @file Tests/Traits/DecoratesRangeInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\DecoratesRangeInterface;
use Korowai\Lib\Ldif\Traits\ExposesRangeInterface;
use Korowai\Lib\Ldif\RangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesRangeInterfaceTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use DecoratesRangeInterface; };
    }

    public function test__uses__ExposesRangeInterface()
    {
        $uses = class_uses(DecoratesRangeInterface::class);
        $this->assertContains(ExposesRangeInterface::class, $uses);
    }

    public function test__coupledRange()
    {
        $location = $this->getMockBuilder(RangeInterface::class)
                         ->getMockForAbstractClass();

        $obj = $this->getTestObject();
        $this->assertNull($obj->getRange());

        $this->assertSame($obj, $obj->setRange($location));
        $this->assertSame($location, $obj->getRange());
    }
}

// vim: syntax=php sw=4 ts=4 et:
