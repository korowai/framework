<?php
/**
 * @file Tests/Util/preprocessingTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests\Util;

use function Korowai\Lib\Ldif\Util\ppAsmPieces;
use function Korowai\Lib\Ldif\Util\ppRmRe;
use function Korowai\Lib\Ldif\Util\ppRmLnCont;
use function Korowai\Lib\Ldif\Util\ppRmComments;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PreprocessingTest extends TestCase
{
    //
    // ppRmRe
    //
    public function test__ppRmRe__noim__00()
    {
        $new = ppRmRe('/foo/', "");
        $this->assertEquals($new, "");
    }

    public function test__ppRmRe__noim__01()
    {
        $new = ppRmRe('/\n /m', "first\n  second\n  third");
        $this->assertEquals($new, "first second third");
    }

    public function test__ppRmRe__00()
    {
        $new = ppRmRe('/foo/', "", $im);
        $this->assertEquals($new, "");
        $this->assertEquals($im, []);
    }

    public function test__ppRmRe__01()
    {
        $new = ppRmRe('/foo/', "bar geez", $im);
        $this->assertEquals($new, "bar geez");
        $this->assertEquals($im, [[0,0]]);
    }

    public function test__ppRmRe__02()
    {
        $new = ppRmRe('/\n /m', "first\n  second\n  third", $im);
        $this->assertEquals($new, "first second third");
        $this->assertEquals($im, [[0,0], [5, 7], [12, 16]]);
    }

    public function test__ppRmRe__03()
    {
        //      00000000001 111111 111222222 22223333 3 33333444444 4444555555
        //      01234567890 123456 789012345 67890123 4 56789012345 6789012345
        $src = "# comment 1\nfirst\n  second\n  third\n\n# two-line\n  comment";
        $str = ppRmRe('/\n /m',$src, $im);
        //                         00000000001 1111111112222222222 3 3333333334444444444
        //                         01234567890 1234567890123456789 0 1234567890123456789
        $this->assertEquals($str, "# comment 1\nfirst second third\n\n# two-line comment");
        $this->assertEquals($im, [[0,0], [17,19], [24,28], [42,48]]);

        $str = ppRmRe('/^#[^\n]*\n?/m', $str, $im);
        //                         000000000011111111 1 1
        //                         012345678901234567 8 9
        $this->assertEquals($str, "first second third\n\n");
        $this->assertEquals($im, [[0,12], [5,19], [12,28], [30, 48]]);
    }

    //
    // ppRmLnCont
    //
    public function test__ppRmLnCont__noim_00()
    {

        $src = "a text\nwithout\nln cont";
        $str = ppRmLnCont($src);

        $this->assertEquals($str, $src);
    }

    public function test__ppRmLnCont__noim_01()
    {

        $src = "a text\n  with\n  ln conts";
        $str = ppRmLnCont($src);

        $this->assertEquals($str, "a text with ln conts");
    }

    public function test__ppRmLnCont__00()
    {

        $src = "a text\nwithout\nln cont";
        $str = ppRmLnCont($src, $im);

        $this->assertEquals($str, $src);
        $this->assertEquals($im, [[0,0]]);
    }

    public function test__ppRmLnCont__01()
    {

        //      000000 0000111 1111111222
        //      012345 6789012 34567890123
        $src = "a text\n  with\n  ln conts";
        $str = ppRmLnCont($src, $im);

        //                         00000000001111111111
        //                         01234567890123456789
        $this->assertEquals($str, "a text with ln conts");
        $this->assertEquals($im, [[0,0], [6, 8], [11, 15]]);
    }

    //
    // ppRmComments
    //
    public function test__ppRmComments__noim__00()
    {

        $src = "A text without comments";
        $str = ppRmComments($src);

        $this->assertEquals($str, $src);
    }

    public function test__ppRmComments__noim__01()
    {

        $src = "# comment 1\ndn: cn=admin,dc=example,dc=org\n\n# comment 2\ndn: ou=people,dc=example,dc=org";
        $str = ppRmComments($src);

        $this->assertEquals($str, "dn: cn=admin,dc=example,dc=org\n\ndn: ou=people,dc=example,dc=org");
    }

    public function test__ppRmComments__00()
    {

        $src = "A text without comments";
        $str = ppRmComments($src, $im);

        $this->assertEquals($str, $src);
        $this->assertEquals($im, [[0,0]]);
    }

    public function test__ppRmComments__01()
    {

        //      00000000001 1111111112222222222333333333344 4 444444455555 55555666666666677777777778888888
        //      01234567890 1234567890123456789012345678901 2 345678901234 56789012345678901234567890123456
        $src = "# comment 1\ndn: cn=admin,dc=example,dc=org\n\n# comment 2\ndn: ou=people,dc=example,dc=org";
        $str = ppRmComments($src, $im);

        //                         000000000011111111112222222222 3 33333333344444444445555555555666
        //                         012345678901234567890123456789 0 12345678901234567890123456789012
        $this->assertEquals($str, "dn: cn=admin,dc=example,dc=org\n\ndn: ou=people,dc=example,dc=org");
        $this->assertEquals($im, [[0,12], [32,56]]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
