<?php
/**
 * @file tests/Traits/MatchesPatternsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\StateInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserError;
//use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Lib\Rfc\Rule;
use Korowai\Testing\Lib\Rfc\RuleSet1;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class MatchesPatternsTest extends TestCase
{
    public function getTestObject()
    {
        $parser = new class {
            use MatchesPatterns { matchString as public; }
        };
        return $parser;
    }

    protected function configureLocationMock(LocationInterface $location, array $case)
    {
        $location->expects($this->once())
                 ->method('getString')
                 ->with()
                 ->willReturn($case[1]);
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn($case[3] ?? 0);
    }

    protected function configureCursorMock(CursorInterface $cursor, array $case, ?int $expMoveTo)
    {
        $this->configureLocationMock($cursor, $case);

        if ($expMoveTo !== null) {
            $cursor->expects($this->once())
                   ->method('moveTo')
                   ->with($expMoveTo);
        } else {
            $cursor->expects($this->never())
                   ->method('moveTo');
        }
    }

    protected function createLocationMock(array $case)
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $this->configureLocationMock($location, $case);
        return $location;
    }

    protected function createCursorMock(array $case, ?int $expMoveTo)
    {
        $cursor = $this->getMockBuilder(CursorInterface::class)
                         ->getMockForAbstractClass();
        $this->configureCursorMock($cursor, $case, $expMoveTo);
        return $cursor;
    }

    public function matchAt__cases()
    {
        return [
            [['//', ''], ['']],
            [['/foo/', 'asdf asdf'], []],
            [['/(\w+)bar/', 'foo rabarbar geez'], ['rabarbar', 'rabar']],
            [['/(\w+)bar/', 'foo rabarbar geez', PREG_OFFSET_CAPTURE], [['rabarbar', 4], ['rabar', 4]]],
            [['/(\w+)bar/', 'foo rabarbar geez', 0, 6], ['barbar', 'bar']],
        ];
    }

    /**
     * @dataProvider matchAt__cases
     */
    public function test__matchAt(array $case, array $expected)
    {
        $location = $this->createLocationMock($case);
        $this->configureLocationMock($location, $case);

        $parser = $this->getTestObject();

        $args = array_merge([$case[0], $location], count($case) > 2 ? [$case[2]] : []);
        $this->assertSame($expected, $parser->matchAt(...$args));
    }

    /**
     * @dataProvider matchAt__cases
     */
    public function test__matchAtOrThrow(array $case, array $expected)
    {
        $location = $this->createLocationMock($case);

        $parser = $this->getTestObject();

        $args = array_merge([$case[0], $location, "error error"], count($case) > 2 ? [$case[2]] : []);

        if ($expected === []) {
            $this->expectException(ParserError::class);
            $this->expectExceptionMessage("error error");
            $parser->matchAtOrThrow(...$args);
        } else {
            $this->assertSame($expected, $parser->matchAtOrThrow(...$args));
        }
    }

    public function matchAhead__cases()
    {
        return [
            [['//', ''], [['', 0]], 0],
            [['/foo/', 'asdf asdf'], []],
            [['/(\w+)bar/', 'foo rabarbar geez'], [['rabarbar', 4], ['rabar', 4]], 12],
            [['/(\w+)bar/', 'foo rabarbar geez', PREG_OFFSET_CAPTURE], [['rabarbar', 4], ['rabar', 4]], 12],
            [['/(\w+)bar/', 'foo rabarbar geez', 0, 6], [['barbar', 6], ['bar', 6]], 12],
        ];
    }

    /**
     * @dataProvider matchAhead__cases
     */
    public function test__matchAhead(array $case, array $expected, int $expMoveTo = null)
    {
        $cursor = $this->createCursorMock($case, $expMoveTo);

        $parser = $this->getTestObject();

        $args = array_merge([$case[0], $cursor], count($case) > 2 ? [$case[2]] : []);
        $this->assertSame($expected, $parser->matchAhead(...$args));
    }

    /**
     * @dataProvider matchAhead__cases
     */
    public function test__matchAheadOrThrow(array $case, array $expected, int $expMoveTo = null)
    {
        $cursor = $this->createCursorMock($case, $expMoveTo);

        $parser = $this->getTestObject();

        $args = array_merge([$case[0], $cursor, "error error"], count($case) > 2 ? [$case[2]] : []);

        if ($expected === []) {
            $this->expectException(ParserError::class);
            $this->expectExceptionMessage("error error");
            $parser->matchAheadOrThrow(...$args);
        } else {
            $this->assertSame($expected, $parser->matchAheadOrThrow(...$args));
        }
    }

    /**
     * @dataProvider matchAt__cases
     */
    public function test__matchString(array $case, array $expected)
    {
        $parser = $this->getTestObject();
        $this->assertSame($expected, $parser->matchString(...$case));
    }
}

// vim: syntax=php sw=4 ts=4 et:
