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

use Korowai\Component\Ldif\LdifSnip;
use Korowai\Component\Ldif\LdifLine;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifLineTest extends TestCase
{
    public function test_baseClass()
    {
        $this->assertTrue(is_subclass_of(LdifLine::class, LdifSnip::class));
    }

    public function test_emptyLine_1()
    {
        $line = new LdifLine("");
        $this->assertEquals($line->getContent(), "");
        $this->assertEquals((string)$line, "");
        $this->assertEquals($line->getPieces(), [""]);
        $this->assertEquals($line->getUnfolded(), "");
        $this->assertEquals($line->getStartLine(), 0);
        $this->assertEquals($line->getLinesCount(), 1);
        $this->assertEquals($line->getEndLine(), 1);
        $this->assertEquals($line->getLineAt(0), 1);
        $this->assertEquals($line->getLineAt(1), 1);
    }


    public function test_emptyLine_2()
    {
        $line = new LdifLine("", 123);
        $this->assertEquals($line->getContent(), "");
        $this->assertEquals((string)$line, "");
        $this->assertEquals($line->getPieces(), [""]);
        $this->assertEquals($line->getUnfolded(), "");
        $this->assertEquals($line->getStartLine(), 123);
        $this->assertEquals($line->getLinesCount(), 1);
        $this->assertEquals($line->getEndLine(), 124);
        $this->assertEquals($line->getLineAt(0), 124);
        $this->assertEquals($line->getLineAt(1), 124);
    }


    public function test_singleLine_1()
    {
        //                    000000000011111111112222222
        //                    012345678901234567890123456
        $line = new LdifLine("test line without any folds", 123);
        $this->assertEquals($line->getContent(), "test line without any folds");
        $this->assertEquals((string)$line, "test line without any folds");
        $this->assertEquals($line->getPieces(), ["test line without any folds"]);
        $this->assertEquals($line->getUnfolded(), "test line without any folds");
        $this->assertEquals($line->getStartLine(), 123);
        $this->assertEquals($line->getLinesCount(), 1);
        $this->assertEquals($line->getEndLine(), 124);
        $this->assertEquals($line->getLineAt( 0), 123);
        $this->assertEquals($line->getLineAt( 1), 123);
        $this->assertEquals($line->getLineAt(26), 123);
        $this->assertEquals($line->getLineAt(27), 124);
    }


    public function test_multiLine_1()
    {
        //                    000000000011111111112222222
        //                    012345678901234567890123456
        $line = new LdifLine("first piece\n  second piece\r\n  third piece", 123);
        $this->assertEquals($line->getContent(), "first piece\n  second piece\r\n  third piece");
        $this->assertEquals((string)$line, "first piece\n  second piece\r\n  third piece");
        $this->assertEquals($line->getPieces(), ["first piece", " second piece", " third piece"]);
        $this->assertEquals($line->getUnfolded(), "first piece second piece third piece");
        $this->assertEquals($line->getStartLine(), 123);
        $this->assertEquals($line->getLinesCount(), 3);
        $this->assertEquals($line->getEndLine(), 126);
        $this->assertEquals($line->getLineAt( 0), 123);
        $this->assertEquals($line->getLineAt( 8), 123);
        $this->assertEquals($line->getLineAt(11), 124);
        $this->assertEquals($line->getLineAt(23), 124);
        $this->assertEquals($line->getLineAt(24), 125);
        $this->assertEquals($line->getLineAt(35), 125);
        $this->assertEquals($line->getLineAt(36), 126);
    }


    public function test_sepLine_1()
    {
        $line = new LdifLine("\n");
        $this->assertEquals($line->getContent(), "\n");
        $this->assertEquals((string)$line, "\n");
        $this->assertEquals($line->getPieces(), ["\n"]);
        $this->assertEquals($line->getUnfolded(), "\n");
        $this->assertEquals($line->getStartLine(), 0);
        $this->assertEquals($line->getLinesCount(), 1);
        $this->assertEquals($line->getEndLine(), 1);
        $this->assertEquals($line->getLineAt(0), 0);
        $this->assertEquals($line->getLineAt(1), 1);
    }


    public function test_sepLine_2()
    {
        $line = new LdifLine("\n", 123);
        $this->assertEquals($line->getContent(), "\n");
        $this->assertEquals((string)$line, "\n");
        $this->assertEquals($line->getPieces(), ["\n"]);
        $this->assertEquals($line->getUnfolded(), "\n");
        $this->assertEquals($line->getStartLine(), 123);
        $this->assertEquals($line->getLinesCount(), 1);
        $this->assertEquals($line->getEndLine(), 124);
        $this->assertEquals($line->getLineAt(0), 123);
        $this->assertEquals($line->getLineAt(1), 124);
    }
}

// vim: syntax=php sw=4 ts=4 et:
