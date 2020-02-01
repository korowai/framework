<?php
/**
 * @file Tests/RFC1738Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\RFC1738;
use function Korowai\Lib\Compat\preg_match;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RFC1738Test extends TestCase
{
    public function HEXCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'b', 'c', 'd', 'e', 'f', 'A', 'B', 'C', 'D', 'E', 'F',
            '0', '1', '1', '3', '4', '5', '6', '7', '8', '9'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'x', '%'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider HEXCases
     */
    public function test__HEX(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::HEX.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function RESERVEDCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            ';', '/', '?', ':', '@', '&', '='
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'A', '.'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider RESERVEDCases
     */
    public function test__RESERVED(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::RESERVED.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function SAFECases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '$', '-', '_', '.', '+'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '=', 'a'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider SAFECases
     */
    public function test__SAFE(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::SAFE.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function EXTRACases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '!', '*', '\'', '(', ')', ','
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'a', '?'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider EXTRACases
     */
    public function test__EXTRA(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::EXTRA.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function NATIONALCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '{', '}', '|', '\\', '^', '~', '[', ']', '`'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'a', '!'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider NATIONALCases
     */
    public function test__NATIONAL(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::NATIONAL.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function ALPHACases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '$', ' ', 'ł'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider ALPHACases
     */
    public function test__ALPHA(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::ALPHA.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function UNRESERVEDCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            '$', '-', '_', '.', '+', '!', '*', '\'', '(', ')', ','
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'ł',
            '{', '}', '|', '\\', '^', '~', '[', ']', '`',
            ';', '/', '?', ':', '@', '&', '='
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider UNRESERVEDCases
     */
    public function test__UNRESERVED(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::UNRESERVED.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function RESERVEDUNRESERVEDCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            ';', '/', '?', ':', '@', '&', '=',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            '$', '-', '_', '.', '+', '!', '*', '\'', '(', ')', ','
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'ł',
            '{', '}', '|', '\\', '^', '~', '[', ']', '`'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider RESERVEDUNRESERVEDCases
     */
    public function test__RESERVEDUNRESERVED(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::RESERVEDUNRESERVED.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function ESCAPECases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '%00', '%01', '%1a', '%a3', '%ff', '%e5', '%ED', '%aF', '%fA'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', '12', '%1', '%X2', '%2X'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider ESCAPECases
     */
    public function test__ESCAPE(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::ESCAPE.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function UCHARCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            '$', '-', '_', '.', '+', '!', '*', '\'', '(', ')', ',',
            '%00', '%01', '%1a', '%a3', '%ff', '%e5', '%ED', '%aF', '%fA'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', '12', '%1', '%X2', '%2X', '%%', '#',
            ';', '/', '?', ':', '@', '&', '=',
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider UCHARCases
     */
    public function test__UCHAR(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::UCHAR.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function XCHARCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            ';', '/', '?', ':', '@', '&', '=',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            '$', '-', '_', '.', '+', '!', '*', '\'', '(', ')', ',',
            '%00', '%01', '%1a', '%a3', '%ff', '%e5', '%ED', '%aF', '%fA'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', '12', '%1', '%X2', '%2X', '%%', '#'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider XCHARCases
     */
    public function test__XCHAR(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::XCHAR.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function PASSWORDCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '', 'a', 'jSm!th', 'a%3f*', 'abc?;&=',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '%', '%1', '%X2', '%2X', '%%', '#'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider PASSWORDCases
     */
    public function test__PASSWORD(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::PASSWORD.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function USERCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '', 'a', 'jSm!th', 'a%3f*', 'abc?;&=',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '%', '%1', '%X2', '%2X', '%%', '#'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider USERCases
     */
    public function test__USER(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::USER.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function URLPATHCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '', 'a', 'aSdf', 'a%3f*', 'abc?;&=', 'foo/bar?x=20&y=ff:sdf'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '%', '%1', '%X2', '%2X', '%%', '#'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider URLPATHCases
     */
    public function test__URLPATH(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::URLPATH.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function PORTCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '1', '13', '1234567'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'a', '%',
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider PORTCases
     */
    public function test__PORT(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::PORT.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function HOSTNUMBERCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '10.20.3.128'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', 'a', '%', '1', '123', '1.', '1.2', '1.2.', '1.2.3', '1.2.3.' ,
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider HOSTNUMBERCases
     */
    public function test__HOSTNUMBER(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::HOSTNUMBER.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function TOPLABELCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'a12-43-x34', 'foo1-bar2-g', 'foo1-bar2-3'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '1', '%', 'ł', 'a12-', '1ss-dfs'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider TOPLABELCases
     */
    public function test__TOPLABEL(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::TOPLABEL.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function DOMAINLABELCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'a12-43-x34', 'foo1-bar2-g', 'foo1-bar2-3', '1', '1ss-dfs'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'a12-'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider DOMAINLABELCases
     */
    public function test__DOMAINLABEL(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::DOMAINLABEL.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function HOSTNAMECases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'a12-43-x34', 'foo1-bar2-g', 'foo1-bar2-3', '1.a12-43-x34', '1ss-dfs.foo1-bar2-3'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'a12-', '1', '1ss-dfs', '10.20.3.123'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider HOSTNAMECases
     */
    public function test__HOSTNAME(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::HOSTNAME.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function HOSTCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'a12-43-x34', 'foo1-bar2-g', 'foo1-bar2-3', '1.a12-43-x34', '1ss-dfs.foo1-bar2-3', '10.20.3.123'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'a12-', '1', '1ss-dfs'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider HOSTCases
     */
    public function test__HOST(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::HOST.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function HOSTPORTCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'a:123',
            'a12-43-x34', 'a12-43-x34:123',
            '1.a12-43-x34', '1.a12-43-x34:123',
            '1ss-dfs.foo1-bar2-3', '1ss-dfs.foo1-bar2-3:123', 
            '10.20.3.123', '10.20.3.123:123'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'a12-', '1:123', '1ss-dfs:123'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider HOSTPORTCases
     */
    public function test__HOSTPORT(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::HOSTPORT.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function LOGINCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', 'a:123',
            'a12-43-x34', 'a12-43-x34:123',
            '1.a12-43-x34', '1.a12-43-x34:123',
            '1ss-dfs.foo1-bar2-3', '1ss-dfs.foo1-bar2-3:123',
            '10.20.3.123', '10.20.3.123:123',
            'jSmith@1ss-dfs.foo1-bar2-3',
            'jSmith@1ss-dfs.foo1-bar2-3:123',
            'jSmith:$3crE7@1ss-dfs.foo1-bar2-3',
            'jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123',
            '@1ss-dfs.foo1-bar2-3',
            '@1ss-dfs.foo1-bar2-3:123',
            ':$3crE7@1ss-dfs.foo1-bar2-3',
            ':$3crE7@1ss-dfs.foo1-bar2-3:123',
            'jSmith:@1ss-dfs.foo1-bar2-3',
            'jSmith:@1ss-dfs.foo1-bar2-3:123',
            ':@1ss-dfs.foo1-bar2-3',
            ':@1ss-dfs.foo1-bar2-3:123',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'a12-', '1:123', '1ss-dfs:123',
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider LOGINCases
     */
    public function test__LOGIN(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::LOGIN.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function IP_SCHEMEPARTCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '//a', '//a:123',
            '//a12-43-x34', '//a12-43-x34:123',
            '//1.a12-43-x34', '//1.a12-43-x34:123',
            '//1ss-dfs.foo1-bar2-3', '//1ss-dfs.foo1-bar2-3:123',
            '//10.20.3.123', '//10.20.3.123:123',
            '//10.20.3.123/', '//10.20.3.123:123/',
            '//10.20.3.123/foo/bar?arg1=geez,arg2=dood',
            '//10.20.3.123:123/foo/bar?arg1=geez,arg2=dood',
            '//jSmith@1ss-dfs.foo1-bar2-3',
            '//jSmith@1ss-dfs.foo1-bar2-3/',
            '//jSmith@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//jSmith@1ss-dfs.foo1-bar2-3:123',
            '//jSmith@1ss-dfs.foo1-bar2-3:123/',
            '//jSmith@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3/',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123/',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//@1ss-dfs.foo1-bar2-3',
            '//@1ss-dfs.foo1-bar2-3/',
            '//@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//@1ss-dfs.foo1-bar2-3:123',
            '//@1ss-dfs.foo1-bar2-3:123/',
            '//@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//:$3crE7@1ss-dfs.foo1-bar2-3',
            '//:$3crE7@1ss-dfs.foo1-bar2-3/',
            '//:$3crE7@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//:$3crE7@1ss-dfs.foo1-bar2-3:123',
            '//:$3crE7@1ss-dfs.foo1-bar2-3:123/',
            '//:$3crE7@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:@1ss-dfs.foo1-bar2-3',
            '//jSmith:@1ss-dfs.foo1-bar2-3/',
            '//jSmith:@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:@1ss-dfs.foo1-bar2-3:123',
            '//jSmith:@1ss-dfs.foo1-bar2-3:123/',
            '//jSmith:@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//:@1ss-dfs.foo1-bar2-3',
            '//:@1ss-dfs.foo1-bar2-3/',
            '//:@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//:@1ss-dfs.foo1-bar2-3:123',
            '//:@1ss-dfs.foo1-bar2-3:123/',
            '//:@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'a12-', '//a12-', '1:123', '//1:123', '1ss-dfs:123', '//1ss-dfs:123',
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider IP_SCHEMEPARTCases
     */
    public function test__IP_SCHEMEPART(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::IP_SCHEMEPART.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function SCHEMEPARTCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '',
            ';/?:@&=asdf123%01-.+!', // XCHAR*
            '//a', '//a:123',
            '//a12-43-x34', '//a12-43-x34:123',
            '//1.a12-43-x34', '//1.a12-43-x34:123',
            '//1ss-dfs.foo1-bar2-3', '//1ss-dfs.foo1-bar2-3:123',
            '//10.20.3.123', '//10.20.3.123:123',
            '//10.20.3.123/', '//10.20.3.123:123/',
            '//10.20.3.123/foo/bar?arg1=geez,arg2=dood',
            '//10.20.3.123:123/foo/bar?arg1=geez,arg2=dood',
            '//jSmith@1ss-dfs.foo1-bar2-3',
            '//jSmith@1ss-dfs.foo1-bar2-3/',
            '//jSmith@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//jSmith@1ss-dfs.foo1-bar2-3:123',
            '//jSmith@1ss-dfs.foo1-bar2-3:123/',
            '//jSmith@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3/',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123/',
            '//jSmith:$3crE7@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//@1ss-dfs.foo1-bar2-3',
            '//@1ss-dfs.foo1-bar2-3/',
            '//@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//@1ss-dfs.foo1-bar2-3:123',
            '//@1ss-dfs.foo1-bar2-3:123/',
            '//@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//:$3crE7@1ss-dfs.foo1-bar2-3',
            '//:$3crE7@1ss-dfs.foo1-bar2-3/',
            '//:$3crE7@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//:$3crE7@1ss-dfs.foo1-bar2-3:123',
            '//:$3crE7@1ss-dfs.foo1-bar2-3:123/',
            '//:$3crE7@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:@1ss-dfs.foo1-bar2-3',
            '//jSmith:@1ss-dfs.foo1-bar2-3/',
            '//jSmith:@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//jSmith:@1ss-dfs.foo1-bar2-3:123',
            '//jSmith:@1ss-dfs.foo1-bar2-3:123/',
            '//jSmith:@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
            '//:@1ss-dfs.foo1-bar2-3',
            '//:@1ss-dfs.foo1-bar2-3/',
            '//:@1ss-dfs.foo1-bar2-3/foo/bar?arg1=geez,arg2=dood',
            '//:@1ss-dfs.foo1-bar2-3:123',
            '//:@1ss-dfs.foo1-bar2-3:123/',
            '//:@1ss-dfs.foo1-bar2-3:123/foo/bar?arg1=geez,arg2=dood',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            'ł', 
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider SCHEMEPARTCases
     */
    public function test__SCHEMEPART(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::SCHEMEPART.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function SCHEMECases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'a', '0', '-', '+', '.', 'abc', '-sb', '.a-1+2',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'A', '?', '*'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider SCHEMECases
     */
    public function test__SCHEME(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::SCHEME.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function GENERICURLCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'foo:', 'foo:bar',
            'foo://jsmith:j$m!th@example.com/foo/bar?arg1=geez,arg2=dood'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '%', 'ł', 'A', '?', '*', 'foo123'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider GENERICURLCases
     */
    public function test__GENERICURL(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::GENERICURL.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function FSEGMENTCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '', 'asDF23!*\'(),$_.+-%3E?:@&='
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '%', 'ł', 'a%xw', 's\/x'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider FSEGMENTCases
     */
    public function test__FSEGMENT(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::FSEGMENT.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function FPATHCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            '', '/', '/foo', 'asDF23', 'asDF23!*\'(/),$_.+-%3E?:@&=', 'foo/bar'
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '%', 'ł', 'a%xw', 's\/x'
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider FPATHCases
     */
    public function test__FPATH(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::FPATH.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }

    public function FILEURLCases()
    {
        $matchingCases = array_map(function ($str) {
            return [$str, 1, [$str]];
        }, [
            'file:///', 'file:///foo', 'file:///asDF23',
            'file:///asDF23!*\'(/),$_.+-%3E?:@&=', 'file:///foo/bar',
            'file://localhost/foo/bar',
            'file://ex1.com/foo/bar',
        ]);
        $nonMatchingCases = array_map(function ($str) {
            return [$str, 0, []];
        }, [
            '', '/', '/foo', 'asDF23', 'asDF23!*\'(/),$_.+-%3E?:@&=',
            'file:', 'file://', 'file:///%', 'file:///ł', 'file:///a%xw', 'file:///s\/x',
            'file://jsmith:JSm!th@localhost/',
            'file://10.20.31.123:22/foo/bar',
        ]);
        return array_merge($matchingCases, $nonMatchingCases);
    }

    /**
     * @dataProvider FILEURLCases
     */
    public function test__FILEURL(string $string, $expResult, $expMatches)
    {
        $result = preg_match('/^'.RFC1738::FILEURL.'$/', $string, $matches);
        $this->assertSame($expResult, $result);
        $this->assertSame($expMatches, $matches);
    }
}

// vim: syntax=php sw=4 ts=4 et:
