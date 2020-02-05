<?php
/**
 * @file Tests/Traits/MaintainsParserStateTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\MaintainsParserState;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class MaintainsParserStateTest extends TestCase
{
    protected function getTestObject()
    {
        return new class {
            use MaintainsParserState;
        };
    }

    public static function errorAtOffset__cases()
    {
        return [
            ['syntax error'],
            ['syntax error', null],
            ['syntax error',123],
        ];
    }

    /**
     * @dataProvider errorAtOffset__cases
     */
    public function test__errorAtOffset(string $message, ...$tail)
    {
        $object = $this->getTestObject();
        $state = $this->getMockBuilder(ParserStateInterface::class)->getMockForAbstractClass();
        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        $location = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();

        $state->expects($this->atLeastOnce())
              ->method('getCursor')
              ->with()
              ->willReturn($cursor);

        if (($offset = $tail[0] ?? null) === null) {
            $cursor->expects($this->never())
                   ->method('moveTo');
        } else {
            $cursor->expects($this->once())
                   ->method('moveTo')
                   ->with($offset);
        }

        $cursor->expects($this->once())
               ->method('getClonedLocation')
               ->with()
               ->willReturn($location);

        $state->expects($this->once())
              ->method('appendError')
              ->with($this->callback(function ($arg) use ($location, $message) {
                  return ($arg instanceof ParserError) &&
                         ($arg->getMessage() == $message) &&
                         ($arg->getSourceLocation() === $location);
                }));

        $object->errorAtOffset($state, $message, ...$tail);
    }

    public function test__errorHere()
    {
        $object = $this->getTestObject();
        $state = $this->getMockBuilder(ParserStateInterface::class)->getMockForAbstractClass();
        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        $location = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();

        $message = 'syntax error';

        $state->expects($this->atLeastOnce())
              ->method('getCursor')
              ->with()
              ->willReturn($cursor);

        $cursor->expects($this->once())
               ->method('getClonedLocation')
               ->with()
               ->willReturn($location);

        $state->expects($this->once())
              ->method('appendError')
              ->with($this->callback(function ($arg) use ($location, $message) {
                  return ($arg instanceof ParserError) &&
                         ($arg->getMessage() == $message) &&
                         ($arg->getSourceLocation() === $location);
                }));

        $object->errorHere($state, $message);
    }
}

// vim: syntax=php sw=4 ts=4 et:
