<?php
/**
 * @file Tests/Traits/WrapsSourceLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\WrapsSourceLocation;
use Korowai\Lib\Ldif\Traits\ExposesSourceLocation;
use Korowai\Lib\Ldif\SourceLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class WrapsSourceLocationTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use WrapsSourceLocation; };
    }

    public function test__uses__ExposesSourceLocation()
    {
        $uses = class_uses(WrapsSourceLocation::class);
        $this->assertContains(ExposesSourceLocation::class, $uses);
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
