<?php
/**
 * @file Tests/ParserStateTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\ParserState;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserError;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserStateTest extends TestCase
{
    public function test__implements__ParserStateInterface()
    {
        $this->assertImplementsInterface(ParserStateInterface::class, ParserState::class);
    }

    public function constructCases()
    {
        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        return [
            [$cursor],
            [$cursor, []],
            [$cursor, null],
            [$cursor, ['E']],
            [$cursor, ['E'], []],
            [$cursor, ['E'], null],
            [$cursor, ['E'], ['R']],
        ];
    }

    /**
     * @dataProvider constructCases
     */
    public function test__construct(...$args)
    {
        $state = new ParserState(...$args);

        $this->assertSame($args[0], $state->getCursor());

        if (count($args[1] ?? []) === 0) {
            $this->assertSame([], $state->getErrors());
            $this->assertTrue($state->isOk());
        } else {
            $this->assertSame($args[1], $state->getErrors());
            $this->assertFalse($state->isOk());
        }

        if (count($args[2] ?? []) === 0) {
            $this->assertSame([], $state->getRecords());
        } else {
            $this->assertSame($args[2], $state->getRecords());
        }
    }

    protected function createParserState(...$args)
    {
        $cursor = $args[0] ?? $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        return new ParserState($cursor, array_slice($args, 1));
    }

    public function test__cursor()
    {
        $state = $this->createParserState();
        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();

        $this->assertSame($state, $state->setCursor($cursor));
        $this->assertSame($cursor, $state->getCursor());
    }

    public function test__errors()
    {
        $state = $this->createParserState();

        $this->assertSame([], $state->getErrors());
        $this->assertTrue($state->isOk());

        $this->assertSame($state, $state->setErrors(['E']));
        $this->assertSame(['E'], $state->getErrors());
        $this->assertFalse($state->isOk());
    }

    public function test__records()
    {
        $state = $this->createParserState();

        $this->assertSame([], $state->getRecords());

        $this->assertSame($state, $state->setRecords(['R']));
        $this->assertSame(['R'], $state->getRecords());
    }

    public function test__appendError()
    {
        $state = $this->createParserState();
        // Due to a bug in phpunit we can't mock interfaces that extend \Throwable.
        $error = $this->createMock(ParserError::class);

        $this->assertSame([], $state->getErrors());
        $this->assertSame($state, $state->appendError($error));
        $this->assertSame([$error], $state->getErrors());
    }

    public function test__appendRecord()
    {
        $state = $this->createParserState();
        // Due to a bug in phpunit we can't mock interfaces that extend \Throwable.
        $record = $this->getMockBuilder(RecordInterface::class)->getMockForAbstractClass();

        $this->assertSame([], $state->getRecords());
        $this->assertSame($state, $state->appendRecord($record));
        $this->assertSame([$record], $state->getRecords());
    }
}

// vim: syntax=php sw=4 ts=4 et:
