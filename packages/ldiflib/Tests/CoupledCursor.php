<?php
/**
 * @file Tests/CoupledCursorTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\CoupledCursor;
use Korowai\Lib\Ldif\CoupledCursorInterface;
use Korowai\Lib\Ldif\CoupledLocation;
use Korowai\Lib\Ldif\CoupledInput;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CoupledCursorTest extends TestCase
{
    public function test__implements__CoupledCursorInterface()
    {
        $interfaces = class_implements(CoupledCursor::class);
        $this->assertContains(CoupledCursorInterface::class, $interfaces);
    }

    public function test__extends__CoupledLocation()
    {
        $parents = class_parents(CoupledCursor::class);
        $this->assertContains(CoupledLocation::class, $parents);
    }

    public function test__moveBy()
    {
        $input = $this->createMock(CoupledInput::class);
        $cursor = new CoupledCursor($input, 0);

        $this->assertSame(0, $cursor->getByteOffset());

        $cursor->moveBy(2);
        $this->assertSame(2, $cursor->getByteOffset());

        $cursor->moveBy(3);
        $this->assertSame(5, $cursor->getByteOffset());

        $cursor->moveBy(-2);
        $this->assertSame(3, $cursor->getByteOffset());
        $this->assertSame(5, $cursor->getByteOffset());
    }

    public function test__moveTo()
    {
        $input = $this->createMock(CoupledInput::class);
        $cursor = new CoupledCursor($input, 0);

        $this->assertSame(0, $cursor->getByteOffset());

        $cursor->moveTo(2);
        $this->assertSame(2, $cursor->getByteOffset());

        $cursor->moveTo(3);
        $this->assertSame(3, $cursor->getByteOffset());
    }
}

// vim: syntax=php sw=4 ts=4 et:
