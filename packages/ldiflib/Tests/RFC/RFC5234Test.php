<?php
/**
 * @file Tests/RFC5234Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\RFC;

use Korowai\Lib\Ldif\RFC\RFC5234;
use function Korowai\Lib\Compat\preg_match;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @group RFC
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RFC5234Test extends TestCase
{
    public function test__constValues()
    {
        // character lists for character classes
        $this->assertSame('A-Za-z',         RFC5234::ALPHACHARS);
        $this->assertSame('01',             RFC5234::BITCHARS);
        $this->assertSame('\x01-\x7F',      RFC5234::CHARCHARS);
        $this->assertSame('\r',             RFC5234::CRCHARS);
        $this->assertSame('\x00-\x1F\x7F',  RFC5234::CTLCHARS);
        $this->assertSame('\d',             RFC5234::DIGITCHARS);
        $this->assertSame('\dA-F',          RFC5234::HEXDIGCHARS);
        $this->assertSame('\t',             RFC5234::HTABCHARS);
        $this->assertSame('\n',             RFC5234::LFCHARS);
        $this->assertSame('\x00-\xFF',      RFC5234::OCTETCHARS);
        $this->assertSame(' ',              RFC5234::SPCHARS);
        $this->assertSame('\x21-\x7E',      RFC5234::VCHARCHARS);
        $this->assertSame(' \t',            RFC5234::WSPCHARS);

        // Core rules
        $this->assertSame('[A-Za-z]',       RFC5234::ALPHA);
        $this->assertSame('[01]',           RFC5234::BIT);
        $this->assertSame('[\x01-\x7F]',    RFC5234::CHAR);
        $this->assertSame('\r',             RFC5234::CR);
        $this->assertSame('(?:\r\n)',       RFC5234::CRLF);
        $this->assertSame('[\x00-\x1F\x7F]',RFC5234::CTL);
        $this->assertSame('\d',             RFC5234::DIGIT);
        $this->assertSame('"',              RFC5234::DQUOTE);
        $this->assertSame('[\dA-F]',        RFC5234::HEXDIG);
        $this->assertSame('\t',             RFC5234::HTAB);
        $this->assertSame('\n',             RFC5234::LF);
        $this->assertSame('(?:(?:[ \t]|(?:\r\n)[ \t])*)', RFC5234::LWSP);
        $this->assertSame('[\x00-\xFF]',    RFC5234::OCTET);
        $this->assertSame(' ',              RFC5234::SP);
        $this->assertSame('[\x21-\x7E]',    RFC5234::VCHAR);
        $this->assertSame('[ \t]',          RFC5234::WSP);
    }
}

// vim: syntax=php sw=4 ts=4 et:
