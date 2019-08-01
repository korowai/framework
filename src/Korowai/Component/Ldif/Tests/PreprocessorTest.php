<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Component\Ldif\Preprocessor;
use Korowai\Component\Ldif\PpString;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PreprocessorTest extends TestCase
{
    public function test__preprocess_00()
    {
        $source = "";
        $string = (new Preprocessor())->preprocess($source);

        $this->assertInstanceOf(PpString::class, $string);
        $this->assertEquals($string->getSource(), $source);
        $this->assertEquals($string->getString(), $source);
        $this->assertEquals($string->getIndexMap(), []);
    }

    public function test__preprocess_01()
    {
        $source = "dn: cn=admin,dc=example,dc=org\ncn: admin";
        $string = (new Preprocessor())->preprocess($source);

        $this->assertInstanceOf(PpString::class, $string);
        $this->assertEquals($string->getSource(), $source);
        $this->assertEquals($string->getString(), $source);
        $this->assertEquals($string->getIndexMap(), [[0,0]]);
    }

    public function test__preprocess_02()
    {
        //         000000000011111111112222 22222233 3333333344
        //         012345678901234567890123 45678901 2345678901
        $source = "dn: cn=admin,dc=example,\n dc=org\ncn: admin";
        //         000000000011111111112222222222 333333333344
        //         012345678901234567890123456789 012345678901
        $result = "dn: cn=admin,dc=example,dc=org\ncn: admin";

        $string = (new Preprocessor())->preprocess($source);

        $this->assertInstanceOf(PpString::class, $string);
        $this->assertEquals($string->getSource(), $source);
        $this->assertEquals($string->getString(), $result);
        $this->assertEquals($string->getIndexMap(), [[0,0], [24,26]]);
    }

    public function test__preprocess_03()
    {
        //         00000000001 1111111112222 22222233 33333333 444444444455
        //         01234567890 1234567890123 45678901 23456789 012345678901
        $source = "# comment 1\ndn: cn=admin,dc=example,dc=org\n# comment 2";
        //         000000000011111111112222222222 3
        //         012345678901234567890123456789 0
        $result = "dn: cn=admin,dc=example,dc=org\n";

        $string = (new Preprocessor())->preprocess($source);

        $this->assertInstanceOf(PpString::class, $string);
        $this->assertEquals($string->getSource(), $source);
        $this->assertEquals($string->getString(), $result);
        $this->assertEquals($string->getIndexMap(), [[0,12]]);
    }

    public function test__preprocess_04()
    {
        //         0000000000 111111111122 2222222233333333334444444444555
        //         0123456789 012345678901 2345678901234567890123456789012
        $source = "version: 1\n# comment 1\ndn: cn=admin,dc=example,dc=org";
        //         0000000000 1111111111222222222233333333334 4
        //         0123456789 0123456789012345678901234567890 1
        $result = "version: 1\ndn: cn=admin,dc=example,dc=org";

        $string = (new Preprocessor())->preprocess($source);

        $this->assertInstanceOf(PpString::class, $string);
        $this->assertEquals($string->getSource(), $source);
        $this->assertEquals($string->getString(), $result);
        $this->assertEquals($string->getIndexMap(), [[0,0], [11,23]]);
    }

    public function test__preprocess_05()
    {
        //         000000000 0111 1111111222222222233333333 33444444 444455555555
        //         012345678 9012 3456789012345678901234567 89012345 678901234567
        $source = "# comment\n  1\ndn: cn=admin,dc=example,\n dc=org\n# comment 2";
        //         000000000011111111112222222222 3
        //         012345678901234567890123456789 0
        $result = "dn: cn=admin,dc=example,dc=org\n";

        $string = (new Preprocessor())->preprocess($source);

        $this->assertInstanceOf(PpString::class, $string);
        $this->assertEquals($string->getSource(), $source);
        $this->assertEquals($string->getString(), $result);
        $this->assertEquals($string->getIndexMap(), [[0,14], [24,40]]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
