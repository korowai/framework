<?php
/**
 * @file Tests/Traits/MatchesPatternsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\CoupledCursorInterface;
use Korowai\Lib\Ldif\CoupledLocationInterface;
use Korowai\Lib\Ldif\ParserError;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class MatchesPatternsTest extends TestCase
{
    public function getTestObject()
    {
        $obj = new class {
            use MatchesPatterns { matchString as public; }
        };
        return $obj;
    }

    protected function configureLocationMock(CoupledLocationInterface $location, array $case)
    {
        $location->expects($this->once())
                 ->method('getString')
                 ->with()
                 ->willReturn($case[1]);
        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn($case[3] ?? 0);
    }

    protected function configureCursorMock(CoupledCursorInterface $cursor, array $case, ?int $expMoveTo)
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
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $this->configureLocationMock($location, $case);
        return $location;
    }

    protected function createCursorMock(array $case, ?int $expMoveTo)
    {
        $cursor = $this->getMockBuilder(CoupledCursorInterface::class)
                         ->getMockForAbstractClass();
        $this->configureCursorMock($cursor, $case, $expMoveTo);
        return $cursor;
    }

    public function matchAtCases()
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
     * @dataProvider matchAtCases
     */
    public function test__matchAt(array $case, array $expected)
    {
        $location = $this->createLocationMock($case);
        $this->configureLocationMock($location, $case);

        $obj = $this->getTestObject();

        $args = array_merge([$case[0], $location], count($case) > 2 ? [$case[2]] : []);
        $this->assertSame($expected, $obj->matchAt(...$args));
    }

    /**
     * @dataProvider matchAtCases
     */
    public function test__matchAtOrThrow(array $case, array $expected)
    {
        $location = $this->createLocationMock($case);

        $obj = $this->getTestObject();

        $args = array_merge([$case[0], $location, "error error"], count($case) > 2 ? [$case[2]] : []);

        if ($expected === []) {
            $this->expectException(ParserError::class);
            $this->expectExceptionMessage("error error");
            $obj->matchAtOrThrow(...$args);
        } else {
            $this->assertSame($expected, $obj->matchAtOrThrow(...$args));
        }
    }

    public function matchAheadCases()
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
     * @dataProvider matchAheadCases
     */
    public function test__matchAhead(array $case, array $expected, int $expMoveTo = null)
    {
        $cursor = $this->createCursorMock($case, $expMoveTo);

        $obj = $this->getTestObject();

        $args = array_merge([$case[0], $cursor], count($case) > 2 ? [$case[2]] : []);
        $this->assertSame($expected, $obj->matchAhead(...$args));
    }

    /**
     * @dataProvider matchAheadCases
     */
    public function test__matchAheadOrThrow(array $case, array $expected, int $expMoveTo = null)
    {
        $cursor = $this->createCursorMock($case, $expMoveTo);

        $obj = $this->getTestObject();

        $args = array_merge([$case[0], $cursor, "error error"], count($case) > 2 ? [$case[2]] : []);

        if ($expected === []) {
            $this->expectException(ParserError::class);
            $this->expectExceptionMessage("error error");
            $obj->matchAheadOrThrow(...$args);
        } else {
            $this->assertSame($expected, $obj->matchAheadOrThrow(...$args));
        }
    }

    /**
     * @dataProvider matchAtCases
     */
    public function test__matchString(array $case, array $expected)
    {
        $obj = $this->getTestObject();
        $this->assertSame($expected, $obj->matchString(...$case));
    }
}

// vim: syntax=php sw=4 ts=4 et: