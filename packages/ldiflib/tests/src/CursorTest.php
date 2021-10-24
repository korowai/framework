<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Cursor;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\Input;
use Korowai\Lib\Ldif\Location;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Cursor
 *
 * @internal
 */
final class CursorTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    public function testImplementsCursorInterface(): void
    {
        $this->assertImplementsInterface(CursorInterface::class, Cursor::class);
    }

    public function testExtendsLocation(): void
    {
        $this->assertExtendsClass(Location::class, Cursor::class);
    }

    public function testGetClonedLocation(): void
    {
        $input = $this->createMock(Input::class);

        $cursor = new Cursor($input, 123);

        $clone1 = $cursor->getClonedLocation();
        $this->assertInstanceOf(Location::class, $clone1);
        $this->assertNotInstanceOf(Cursor::class, $clone1);
        $this->assertSame($input, $clone1->getInput());
        $this->assertSame(123, $clone1->getOffset());

        $clone2 = $cursor->getClonedLocation(null);
        $this->assertInstanceOf(Location::class, $clone2);
        $this->assertNotInstanceOf(Cursor::class, $clone2);
        $this->assertSame($input, $clone2->getInput());
        $this->assertSame(123, $clone2->getOffset());

        $clone3 = $cursor->getClonedLocation(321);
        $this->assertInstanceOf(Location::class, $clone3);
        $this->assertNotInstanceOf(Cursor::class, $clone3);
        $this->assertSame($input, $clone3->getInput());
        $this->assertSame(321, $clone3->getOffset());
    }

    public function testMoveBy(): void
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

    public function testMoveTo(): void
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
