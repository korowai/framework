<?php
/**
 * @file Tests/CursorTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Cursor;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\Location;
use Korowai\Lib\Ldif\Input;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CursorTest extends TestCase
{
    public function test__implements__CursorInterface()
    {
        $interfaces = class_implements(Cursor::class);
        $this->assertContains(CursorInterface::class, $interfaces);
    }

    public function test__extends__Location()
    {
        $parents = class_parents(Cursor::class);
        $this->assertContains(Location::class, $parents);
    }

    public function test__moveBy()
    {
        $input = $this->createMock(Input::class);
        $cursor = new Cursor($input, 0);

        $this->assertSame(0, $cursor->getOffset());

        $this->assertSame($cursor, $cursor->moveBy(2));
        $this->assertSame(2, $cursor->getOffset());

        $this->assertSame($cursor, $cursor->moveBy(3));
        $this->assertSame(5, $cursor->getOffset());

        $this->assertSame($cursor, $cursor->moveBy(-2));
        $this->assertSame(3, $cursor->getOffset());
    }

    public function test__moveTo()
    {
        $input = $this->createMock(Input::class);
        $cursor = new Cursor($input, 0);

        $this->assertSame(0, $cursor->getOffset());

        $this->assertSame($cursor, $cursor->moveTo(2));
        $this->assertSame(2, $cursor->getOffset());

        $this->assertSame($cursor, $cursor->moveTo(3));
        $this->assertSame(3, $cursor->getOffset());
    }
}

// vim: syntax=php sw=4 ts=4 et:
