<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Compat;

use Korowai\Testing\TestCase;

use Korowai\Lib\Compat\PregException;

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
final class FunctionsTest extends TestCase
{
    /**
     * @covers \Korowai\Lib\Compat\preg_filter
     */
    public function test__preg_filter() : void
    {
        $this->assertSame('foo bar', preg_filter('/baz/', 'bar', 'foo baz'));
        $this->assertNull(preg_filter('/asdf/', 'bar', 'foo baz'));
        $this->assertSame(['foo bar'], preg_filter(['/baz/'], ['bar'], ['foo baz']));
        $this->assertSame('foo bar baz', preg_filter('/baz/', 'bar', 'foo baz baz', 1));
        $this->assertSame('foo bar baz', preg_filter('/baz/', 'bar', 'foo baz baz', 1, $count));
        $this->assertSame(1, $count);
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_filter
     */
    public function test__preg_filter__triggeredError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_filter
     */
    public function test__preg_filter__returnedError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_grep
     */
    public function test__preg_grep() : void
    {
        $this->assertSame([1 => 'foo baz'], preg_grep('/baz/', ['bar', 'foo baz']));
        $this->assertSame([0 => 'bar'], preg_grep('/baz/', ['bar', 'foo baz'], PREG_GREP_INVERT));
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_grep
     */
    public function test__preg_grep__triggeredError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_grep
     */
    public function test__preg_grep__returnedError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_match
     */
    public function test__preg_match() : void
    {
        $this->assertSame(1, preg_match('/bar/', 'foo bar baz bar'));
        $this->assertSame(0, preg_match('/bob/', 'foo bar baz bar'));

        $this->assertSame(1, preg_match('/b(a)r/', 'foo bar baz bar', $matches));
        $this->assertSame(['bar', 'a'], $matches);

        $this->assertSame(1, preg_match('/b(a)r/', 'foo bar baz bar', $matches, PREG_OFFSET_CAPTURE));
        $this->assertSame([['bar', 4], ['a', 5]], $matches);

        $this->assertSame(1, preg_match('/b(a)r/', 'foo bar baz bar', $matches, PREG_OFFSET_CAPTURE, 2));
        $this->assertSame([['bar', 4], ['a', 5]], $matches);

        $this->assertSame(0, preg_match('/b(a)r/', 'foo bar baz', $matches, PREG_OFFSET_CAPTURE, 5));
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_match
     */
    public function test__preg_match__triggeredError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_match
     */
    public function test__preg_match__returnedError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_match_all
     */
    public function test__preg_match_all() : void
    {
        $this->assertSame(2, preg_match_all('/bar/', 'foo bar baz bar'));
        $this->assertSame(0, preg_match_all('/bob/', 'foo bar baz bar'));

        $this->assertSame(2, preg_match_all('/b(a)r/', 'foo bar baz bar', $matches));
        $this->assertSame([['bar', 'bar'], ['a', 'a']], $matches);

        $flags = PREG_OFFSET_CAPTURE|PREG_PATTERN_ORDER;
        $this->assertSame(2, preg_match_all('/b(a)r/', 'foo bar baz bar', $matches, $flags));
        $this->assertSame([[['bar', 4], ['bar', 12]], [['a', 5],['a',13]]], $matches);

        $this->assertSame(2, preg_match_all('/b(a)r/', 'foo bar baz bar', $matches, $flags, 2));
        $this->assertSame([[['bar', 4], ['bar', 12]], [['a', 5],['a',13]]], $matches);

        $this->assertSame(1, preg_match_all('/b(a)r/', 'foo bar baz bar', $matches, $flags, 5));
        $this->assertSame([[['bar', 12]], [['a',13]]], $matches);
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_match_all
     */
    public function test__preg_match_all__triggeredError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_match_all
     */
    public function test__preg_match_all__returnedError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_replace
     */
    public function test__preg_replace() : void
    {
        $this->assertSame('foo bar', preg_replace('/baz/', 'bar', 'foo baz'));
        $this->assertSame(['foo bar'], preg_replace(['/baz/'], ['bar'], ['foo baz']));
        $this->assertSame('foo bar baz', preg_replace('/baz/', 'bar', 'foo baz baz', 1));
        $this->assertSame('foo bar baz', preg_replace('/baz/', 'bar', 'foo baz baz', 1, $count));
        $this->assertSame(1, $count);
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_replace
     */
    public function test__preg_replace__triggeredError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_replace
     */
    public function test__preg_replace__returnedError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_replace_callback
     */
    public function test__preg_replace_callback() : void
    {
        $cb = function (array $matches) : string {
            return 'bar';
        };

        $this->assertSame('foo bar', preg_replace_callback('/baz/', $cb, 'foo baz'));
        $this->assertSame('foo bar baz', preg_replace_callback('/baz/', $cb, 'foo baz baz', 1));
        $this->assertSame('foo bar baz', preg_replace_callback('/baz/', $cb, 'foo baz baz', 1, $count));
        $this->assertSame(1, $count);
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_replace_callback
     */
    public function test__preg_replace_callback__triggeredError() : void
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_replace_callback(): No ending delimiter '*' found");

        $cb = function (array $matches) : string {
            return 'bar';
        };
        try {
            $line = 1 + __line__;
            preg_replace_callback('*', $cb, 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_replace_callback
     */
    public function test__preg_replace_callback__returnedError() : void
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_replace_callback(): Backtrack limit exhaused');

        $cb = function (array $matches) : string {
            return 'bar';
        };
        try {
            $line = 1 + __line__;
            preg_replace_callback('/(?:\D+|<\d+>)*[!?]/', $cb, 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_replace_callback_array
     */
    public function test__preg_replace_callback_array() : void
    {
        $pac = ['/baz/' => function (array $matches) : string {
            return 'bar';
        }];

        $this->assertSame('foo bar', preg_replace_callback_array($pac, 'foo baz'));
        $this->assertSame('foo bar baz', preg_replace_callback_array($pac, 'foo baz baz', 1));
        $this->assertSame('foo bar baz', preg_replace_callback_array($pac, 'foo baz baz', 1, $count));
        $this->assertSame(1, $count);
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_replace_callback_array
     */
    public function test__preg_replace_callback_array__triggeredError() : void
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_replace_callback_array(): No ending delimiter '*' found");

        $pac = ['*' => function (array $matches) : string {
            return 'bar';
        }];
        try {
            $line = 1 + __line__;
            preg_replace_callback_array($pac, 'foo');
        } catch (PregException $e) {
            $this->assertSame($line, $e->getLine());
            $this->assertSame(__file__, $e->getFile());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_replace_callback_array
     */
    public function test__preg_replace_callback_array__returnedError() : void
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_replace_callback_array(): Backtrack limit exhaused');

        $pac = ['/(?:\D+|<\d+>)*[!?]/' => function (array $matches) : string {
            return 'bar';
        }];
        try {
            $line = 1 + __line__;
            preg_replace_callback_array($pac, 'foobar foobar foobar');
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_split
     */
    public function test__preg_split() : void
    {
        $this->assertSame(['foo', 'bar', '', 'baz'], preg_split('/ /', 'foo bar  baz'));
        $this->assertSame(['foo', 'bar', '', 'baz'], preg_split('/ /', 'foo bar  baz', -1));
        $this->assertSame(['foo', 'bar  baz'], preg_split('/ /', 'foo bar  baz', 2));
        $this->assertSame(['foo', 'bar', 'baz'], preg_split('/ /', 'foo bar  baz', -1, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * @covers \Korowai\Lib\Compat\preg_split
     */
    public function test__preg_split__triggeredError() : void
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

    /**
     * @covers \Korowai\Lib\Compat\preg_split
     */
    public function test__preg_split__returnedError() : void
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

// vim: syntax=php sw=4 ts=4 et tw=119:
