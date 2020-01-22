<?php
/**
 * @file Tests/Traits/DecoratesSnippetInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;
use Korowai\Lib\Ldif\Traits\ExposesSnippetInterface;
use Korowai\Lib\Ldif\SnippetInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesSnippetInterfaceTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use DecoratesSnippetInterface; };
    }

    public function test__uses__ExposesSnippetInterface()
    {
        $uses = class_uses(DecoratesSnippetInterface::class);
        $this->assertContains(ExposesSnippetInterface::class, $uses);
    }

    public function test__coupledSnippet()
    {
        $location = $this->getMockBuilder(SnippetInterface::class)
                         ->getMockForAbstractClass();

        $obj = $this->getTestObject();
        $this->assertNull($obj->getSnippet());

        $this->assertSame($obj, $obj->setSnippet($location));
        $this->assertSame($location, $obj->getSnippet());
    }
}

// vim: syntax=php sw=4 ts=4 et:
