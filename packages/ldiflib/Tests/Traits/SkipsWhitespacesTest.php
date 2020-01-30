<?php
/**
 * @file Tests/Traits/SkipsWhitespacesTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\CursorInterface;
//use Korowai\Lib\Ldif\LocationInterface;
//use Korowai\Lib\Ldif\ParserError;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SkipsWhitespacesTest extends TestCase
{
    public function getTestObject()
    {
        $obj = new class {
            use MatchesPatterns;
            use SkipsWhitespaces;
        };
        return $obj;
    }

    public function skipWsCases()
    {
        return [
            [
                [''],
                [
                    'result' => [],
                    'cursor' => [
                        'offset'        => 0,
                        'sourceOffset'  => 0,
                    ]
                ]
            ],

            [
            //    000000000 111111 1 111
            //    012356789 012345 6 789
                ["# tłuszcz\nasdf \t\nz", 4],
            //               01234 5 678
                [
                    'result' => [[" \t\n", 4]],
                    'cursor' => [
                        'offset'        => 7,
                        'sourceOffset'  => 18,
                        'sourceCharOffset' => 17,
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider skipWsCases
     */
    public function test__skipWs(array $source, array $expected)
    {
        $cursor = $this->getCursorFromSource(...$source);
        $parser = $this->getTestObject();
        $result = $parser->skipWs($cursor);
        $this->assertSame($expected['result'], $result);
        $this->assertCursorHas($expected['cursor'], $cursor);
    }

    public function skipFillCases()
    {
        return [
            [
                [''],
                [
                    'result' => [['', 0]],
                    'cursor' => [
                        'offset'        => 0,
                        'sourceOffset'  => 0,
                    ]
                ]
            ],

            [
            //    000000000 111111 1 111
            //    012356789 012345 6 789
                ["# tłuszcz\nasdf \t\nz", 4],
            //               01234 5 678
                [
                    'result' => [[" ", 4]],
                    'cursor' => [
                        'offset'        => 5,
                        'sourceOffset'  => 16,
                        'sourceCharOffset' => 15,
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider skipFillCases
     */
    public function test__skipFill(array $source, array $expected)
    {
        $cursor = $this->getCursorFromSource(...$source);
        $parser = $this->getTestObject();
        $result = $parser->skipFill($cursor);
        $this->assertSame($expected['result'], $result);
        $this->assertCursorHas($expected['cursor'], $cursor);
    }
}

// vim: syntax=php sw=4 ts=4 et:
