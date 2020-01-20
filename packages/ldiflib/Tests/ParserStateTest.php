<?php
/**
 * @file Tests/ParserStateTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\ParserState;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\CoupledCursorInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserStateTest extends TestCase
{
    public function test__implements__ParserStateInterface()
    {
        $interfaces = class_implements(ParserState::class);
        $this->assertContains(ParserStateInterface::class, $interfaces);
    }

    public function test__construct()
    {
        $cursor = $this->getMockBuilder(CoupledCursorInterface::class)
                       ->getMockForAbstractClass();

        $state = new ParserState($cursor);

        $this->assertSame($cursor, $state->getCursor());
        $this->assertSame([], $state->getErrors());
        $this->assertTrue($state->isOk());
    }

    public function test__construct__withErrors()
    {
        $cursor = $this->getMockBuilder(CoupledCursorInterface::class)
                       ->getMockForAbstractClass();

        $state = new ParserState($cursor, ['A']);

        $this->assertSame($cursor, $state->getCursor());
        $this->assertSame(['A'], $state->getErrors());
        $this->assertFalse($state->isOk());
    }
}

// vim: syntax=php sw=4 ts=4 et:
