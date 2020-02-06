<?php
/**
 * @file tests/FunctionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/compatlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Compat;

use Korowai\Testing\TestCase;

use Korowai\Lib\Compat\Exception\PregException;

use function Korowai\Lib\Compat\preg_filter;
use function Korowai\Lib\Compat\preg_grep;
use function Korowai\Lib\Compat\preg_match;
use function Korowai\Lib\Compat\preg_match_all;
use function Korowai\Lib\Compat\preg_replace;
use function Korowai\Lib\Compat\preg_replace_callback;
use function Korowai\Lib\Compat\preg_replace_callback_array;
use function Korowai\Lib\Compat\preg_split;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class FunctionsTest extends TestCase
{
    public function test__preg_filter()
    {
        $this->assertSame('foo bar', preg_filter('/geez/', 'bar', 'foo geez'));
        $this->assertNull(preg_filter('/asdf/', 'bar', 'foo geez'));
        $this->assertSame(['foo bar'], preg_filter(['/geez/'], ['bar'], ['foo geez']));
        $this->assertSame('foo bar geez', preg_filter('/geez/', 'bar', 'foo geez geez', 1));
        $this->assertSame('foo bar geez', preg_filter('/geez/', 'bar', 'foo geez geez', 1, $count));
        $this->assertSame(1, $count);
    }

    public function test__preg_filter__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_filter(): No ending delimiter '*' found");

        try {
            $line = 1 + __line__;
            preg_filter('*', 'bar', 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_filter__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_filter(): Backtrack limit exhaused');

        try {
            $line = 1 + __line__;
            preg_filter('/(?:\D+|<\d+>)*[!?]/', 'xx', 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_grep()
    {
        $this->assertSame([1 => 'foo geez'], preg_grep('/geez/', ['bar', 'foo geez']));
        $this->assertSame([0 => 'bar'], preg_grep('/geez/', ['bar', 'foo geez'], PREG_GREP_INVERT));
    }

    public function test__preg_grep__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_grep(): No ending delimiter '*' found");

        try {
            $line = 1 + __line__;
            preg_grep('*', ['a']);
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_grep__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_grep(): Backtrack limit exhaused');

        try {
            $line = 1 + __line__;
            preg_grep('/(?:\D+|<\d+>)*[!?]/', ['foobar foobar foobar']);
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_match()
    {
        $this->assertSame(1, preg_match('/bar/', 'foo bar geez bar'));
        $this->assertSame(0, preg_match('/bob/', 'foo bar geez bar'));

        $this->assertSame(1, preg_match('/b(a)r/', 'foo bar geez bar', $matches));
        $this->assertSame(['bar', 'a'], $matches);

        $this->assertSame(1, preg_match('/b(a)r/', 'foo bar geez bar', $matches, PREG_OFFSET_CAPTURE));
        $this->assertSame([['bar', 4], ['a', 5]], $matches);

        $this->assertSame(1, preg_match('/b(a)r/', 'foo bar geez bar', $matches, PREG_OFFSET_CAPTURE, 2));
        $this->assertSame([['bar', 4], ['a', 5]], $matches);

        $this->assertSame(0, preg_match('/b(a)r/', 'foo bar geez', $matches, PREG_OFFSET_CAPTURE, 5));
    }

    public function test__preg_match__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_match(): No ending delimiter '*' found");

        try {
            $line = 1 + __line__;
            preg_match('*', 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_match__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_match(): Backtrack limit exhaused');

        try {
            $line = 1 + __line__;
            preg_match('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_match_all()
    {
        $this->assertSame(2, preg_match_all('/bar/', 'foo bar geez bar'));
        $this->assertSame(0, preg_match_all('/bob/', 'foo bar geez bar'));

        $this->assertSame(2, preg_match_all('/b(a)r/', 'foo bar geez bar', $matches));
        $this->assertSame([['bar', 'bar'], ['a', 'a']], $matches);

        $flags = PREG_OFFSET_CAPTURE|PREG_PATTERN_ORDER;
        $this->assertSame(2, preg_match_all('/b(a)r/', 'foo bar geez bar', $matches, $flags));
        $this->assertSame([[['bar', 4], ['bar', 13]], [['a', 5],['a',14]]], $matches);

        $this->assertSame(2, preg_match_all('/b(a)r/', 'foo bar geez bar', $matches, $flags, 2));
        $this->assertSame([[['bar', 4], ['bar', 13]], [['a', 5],['a',14]]], $matches);

        $this->assertSame(1, preg_match_all('/b(a)r/', 'foo bar geez bar', $matches, $flags, 5));
        $this->assertSame([[['bar', 13]], [['a',14]]], $matches);
    }

    public function test__preg_match_all__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_match_all(): No ending delimiter '*' found");

        try {
            $line = 1 + __line__;
            preg_match_all('*', 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_match_all__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_match_all(): Backtrack limit exhaused');

        try {
            $line = 1 + __line__;
            preg_match_all('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_replace()
    {
        $this->assertSame('foo bar', preg_replace('/geez/', 'bar', 'foo geez'));
        $this->assertSame(['foo bar'], preg_replace(['/geez/'], ['bar'], ['foo geez']));
        $this->assertSame('foo bar geez', preg_replace('/geez/', 'bar', 'foo geez geez', 1));
        $this->assertSame('foo bar geez', preg_replace('/geez/', 'bar', 'foo geez geez', 1, $count));
        $this->assertSame(1, $count);
    }

    public function test__preg_replace__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_replace(): No ending delimiter '*' found");

        try {
            $line = 1 + __line__;
            preg_replace('*', 'bar', 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_replace__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_replace(): Backtrack limit exhaused');

        try {
            $line = 1 + __line__;
            preg_replace('/(?:\D+|<\d+>)*[!?]/', 'xx', 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_replace_callback()
    {
        $cb = function (array $matches) : string { return 'bar'; };

        $this->assertSame('foo bar', preg_replace_callback('/geez/', $cb, 'foo geez'));
        $this->assertSame('foo bar geez', preg_replace_callback('/geez/', $cb, 'foo geez geez', 1));
        $this->assertSame('foo bar geez', preg_replace_callback('/geez/', $cb, 'foo geez geez', 1, $count));
        $this->assertSame(1, $count);
    }

    public function test__preg_replace_callback__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_replace_callback(): No ending delimiter '*' found");

        $cb = function (array $matches) : string { return 'bar'; };
        try {
            $line = 1 + __line__;
            preg_replace_callback('*', $cb, 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_replace_callback__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_replace_callback(): Backtrack limit exhaused');

        $cb = function (array $matches) : string { return 'bar'; };
        try {
            $line = 1 + __line__;
            preg_replace_callback('/(?:\D+|<\d+>)*[!?]/', $cb, 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_replace_callback_array()
    {
        $pac = ['/geez/' => function (array $matches) : string { return 'bar'; }];

        $this->assertSame('foo bar', preg_replace_callback_array($pac, 'foo geez'));
        $this->assertSame('foo bar geez', preg_replace_callback_array($pac, 'foo geez geez', 1));
        $this->assertSame('foo bar geez', preg_replace_callback_array($pac, 'foo geez geez', 1, $count));
        $this->assertSame(1, $count);
    }

    public function test__preg_replace_callback_array__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_replace_callback_array(): No ending delimiter '*' found");

        $pac = ['*' => function (array $matches) : string { return 'bar'; }];
        try {
            $line = 1 + __line__;
            preg_replace_callback_array($pac, 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_replace_callback_array__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_replace_callback_array(): Backtrack limit exhaused');

        $pac = ['/(?:\D+|<\d+>)*[!?]/' => function (array $matches) : string { return 'bar'; }];
        try {
            $line = 1 + __line__;
            preg_replace_callback_array($pac, 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__preg_split()
    {
        $this->assertSame(['foo', 'bar', '', 'geez'], preg_split('/ /', 'foo bar  geez'));
        $this->assertSame(['foo', 'bar', '', 'geez'], preg_split('/ /', 'foo bar  geez', -1));
        $this->assertSame(['foo', 'bar  geez'], preg_split('/ /', 'foo bar  geez', 2));
        $this->assertSame(['foo', 'bar', 'geez'], preg_split('/ /', 'foo bar  geez', -1, PREG_SPLIT_NO_EMPTY));
    }

    public function test__preg_split__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_split(): No ending delimiter '*' found");

        try {
            $line = 1 + __line__;
            preg_split('*', 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    public function test__preg_split__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_split(): Backtrack limit exhaused');

        try {
            $line = 1 + __line__;
            preg_split('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
