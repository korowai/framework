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
use Korowai\Lib\Compat\Preg;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers Korowai\Lib\Compat\Preg
 */
final class PregTest extends TestCase
{
    public function test__getPregErrorConst() : void
    {
        $this->assertSame('PREG_NO_ERROR', Preg::getPregErrorConst(PREG_NO_ERROR));
        $this->assertSame('PREG_INTERNAL_ERROR', Preg::getPregErrorConst(PREG_INTERNAL_ERROR));
        $this->assertSame('PREG_BACKTRACK_LIMIT_ERROR', Preg::getPregErrorConst(PREG_BACKTRACK_LIMIT_ERROR));
        $this->assertSame('PREG_RECURSION_LIMIT_ERROR', Preg::getPregErrorConst(PREG_RECURSION_LIMIT_ERROR));
        $this->assertSame('PREG_BAD_UTF8_ERROR', Preg::getPregErrorConst(PREG_BAD_UTF8_ERROR));
        $this->assertSame('PREG_BAD_UTF8_OFFSET_ERROR', Preg::getPregErrorConst(PREG_BAD_UTF8_OFFSET_ERROR));
        $this->assertSame('PREG_JIT_STACKLIMIT_ERROR', Preg::getPregErrorConst(PREG_JIT_STACKLIMIT_ERROR));
        $this->assertSame('Error 23456', Preg::getPregErrorConst(23456));
    }

    public function test__getPregErrorMessage() : void
    {
        $this->assertSame('No error', Preg::getPregErrorMessage(PREG_NO_ERROR));
        $this->assertSame('Internal error', Preg::getPregErrorMessage(PREG_INTERNAL_ERROR));
        $this->assertSame('Backtrack limit exhaused', Preg::getPregErrorMessage(PREG_BACKTRACK_LIMIT_ERROR));
        $this->assertSame('Recursion limit exhaused', Preg::getPregErrorMessage(PREG_RECURSION_LIMIT_ERROR));
        $this->assertSame('Malformed utf-8 data', Preg::getPregErrorMessage(PREG_BAD_UTF8_ERROR));
        $this->assertSame('Offset does not correspond to the begin of a valid utf-8 code', Preg::getPregErrorMessage(PREG_BAD_UTF8_OFFSET_ERROR));
        $this->assertSame('Failed due to limited JIT stack space', Preg::getPregErrorMessage(PREG_JIT_STACKLIMIT_ERROR));
    }

    public function test__callPregFunc() : void
    {
        $this->assertSame(1, Preg::callPregFunc('\preg_match', ['/bar/', 'foo bar baz']));
        $this->assertSame(0, Preg::callPregFunc('\preg_match', ['/bob/', 'foo bar baz']));
    }

    public function test__callPregFunc__triggeredError() : void
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_match(): No ending delimiter '*' found");
        $this->expectExceptionCode(PREG_INTERNAL_ERROR);

        try {
            $line = 1 + __line__;
            Preg::callPregFunc('\preg_match', ['*', 'foo']);
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__callPregFunc__returnedError() : void
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_match(): Backtrack limit exhaused');
        $this->expectExceptionCode(PREG_BACKTRACK_LIMIT_ERROR);

        try {
            $line = 1 + __line__;
            Preg::callPregFunc('\preg_match', ['/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar']);
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
