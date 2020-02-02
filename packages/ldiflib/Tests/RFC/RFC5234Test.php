<?php
/**
 * @file Tests/RFC2234Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\RFC;

use Korowai\Lib\Ldif\RFC\RFC2234;
use function Korowai\Lib\Compat\preg_match;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RFC2234Test extends TestCase
{
    public function test__constValues()
    {
        // character lists for character classes
        $this->assertSame('A-Za-z',         RFC2234::ALPHACHARS);
        $this->assertSame('01',             RFC2234::BITCHARS);
        $this->assertSame('\x01-\x7F',      RFC2234::CHARCHARS);
        $this->assertSame('\r',             RFC2234::CRCHARS);
        $this->assertSame('\x00-\x1F\x7F',  RFC2234::CTLCHARS);
        $this->assertSame('\d',             RFC2234::DIGITCHARS);
        $this->assertSame('\dA-F',          RFC2234::HEXDIGCHARS);
        $this->assertSame('\t',             RFC2234::HTABCHARS);
        $this->assertSame('\n',             RFC2234::LFCHARS);
        $this->assertSame('\x00-\xFF',      RFC2234::OCTETCHARS);
        $this->assertSame(' ',              RFC2234::SPCHARS);
        $this->assertSame('\x21-\x7E',      RFC2234::VCHARCHARS);
        $this->assertSame(' \t',            RFC2234::WSPCHARS);

        // Core rules
        $this->assertSame('[A-Za-z]',       RFC2234::ALPHA);
        $this->assertSame('[01]',           RFC2234::BIT);
        $this->assertSame('[\x01-\x7F]',    RFC2234::CHAR);
        $this->assertSame('\r',             RFC2234::CR);
        $this->assertSame('(?:\r\n)',       RFC2234::CRLF);
        $this->assertSame('[\x00-\x1F\x7F]',RFC2234::CTL);
        $this->assertSame('\d',             RFC2234::DIGIT);
        $this->assertSame('"',              RFC2234::DQUOTE);
        $this->assertSame('[\dA-F]',        RFC2234::HEXDIG);
        $this->assertSame('\t',             RFC2234::HTAB);
        $this->assertSame('\n',             RFC2234::LF);
        $this->assertSame('(?:(?:[ \t]|(?:\r\n)[ \t])*)', RFC2234::LWSP);
        $this->assertSame('[\x00-\xFF]',    RFC2234::OCTET);
        $this->assertSame(' ',              RFC2234::SP);
        $this->assertSame('[\x21-\x7E]',    RFC2234::VCHAR);
        $this->assertSame('[ \t]',          RFC2234::WSP);
    }
}

// vim: syntax=php sw=4 ts=4 et:
