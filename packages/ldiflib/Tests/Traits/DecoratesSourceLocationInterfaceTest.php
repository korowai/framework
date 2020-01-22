<?php
/**
 * @file Tests/Traits/DecoratesSourceLocationInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\DecoratesSourceLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesSourceLocationInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesSourceLocationInterfaceTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use DecoratesSourceLocationInterface; };
    }

    public function test__uses__ExposesSourceLocationInterface()
    {
        $uses = class_uses(DecoratesSourceLocationInterface::class);
        $this->assertContains(ExposesSourceLocationInterface::class, $uses);
    }

    public function test__sourceLocation()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();

        $obj = $this->getTestObject();
        $this->assertNull($obj->getSourceLocation());

        $this->assertSame($obj, $obj->setSourceLocation($location));
        $this->assertSame($location, $obj->getSourceLocation());
    }
}

// vim: syntax=php sw=4 ts=4 et:
