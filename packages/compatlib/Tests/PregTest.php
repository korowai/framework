<?php
/**
 * @file Tests/PregTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\compatlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Compat\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Compat\Exception\PregException;
use Korowai\Lib\Compat\Preg;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PregTest extends TestCase
{
    public function test__getPregErrorConst()
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

    public function test__getPregErrorMessage()
    {
        $this->assertSame('No error', Preg::getPregErrorMessage(PREG_NO_ERROR));
        $this->assertSame('Internal error', Preg::getPregErrorMessage(PREG_INTERNAL_ERROR));
        $this->assertSame('Backtrack limit exhaused', Preg::getPregErrorMessage(PREG_BACKTRACK_LIMIT_ERROR));
        $this->assertSame('Recursion limit exhaused', Preg::getPregErrorMessage(PREG_RECURSION_LIMIT_ERROR));
        $this->assertSame('Malformed utf-8 data', Preg::getPregErrorMessage(PREG_BAD_UTF8_ERROR));
        $this->assertSame('Offset does not correspond to the begin of a valid utf-8 code', Preg::getPregErrorMessage(PREG_BAD_UTF8_OFFSET_ERROR));
        $this->assertSame('Failed due to limited JIT stack space', Preg::getPregErrorMessage(PREG_JIT_STACKLIMIT_ERROR));
    }

    public function test__callPregFunc()
    {
        $this->assertSame(1, Preg::callPregFunc('\preg_match', ['/bar/', 'foo bar geez']));
        $this->assertSame(0, Preg::callPregFunc('\preg_match', ['/bob/', 'foo bar geez']));
    }

    public function test__callPregFunc__triggeredError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage("preg_match(): No ending delimiter '*' found");
        $this->expectExceptionCode(PREG_INTERNAL_ERROR);

        try {
            $line = 1 + __line__;
            Preg::callPregFunc('\preg_match', ['*', 'foo'], 0);
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }

    public function test__callPregFunc__returnedError()
    {
        $this->expectException(PregException::class);
        $this->expectExceptionMessage('preg_match(): Backtrack limit exhaused');
        $this->expectExceptionCode(PREG_BACKTRACK_LIMIT_ERROR);

        try {
            $line = 1 + __line__;
            Preg::callPregFunc('\preg_match', ['/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar'], 0);
        } catch (PregException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            throw $e;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
