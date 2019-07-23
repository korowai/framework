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
use Korowai\Component\Ldif\LdifSep;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifSepTest extends TestCase
{
    public function test_baseClass()
    {
        $this->assertTrue(is_subclass_of(LdifSep::class, LdifSnip::class));
    }

    public function test_sep_1()
    {
        $line = new LdifSep("\n", 123);
        $this->assertEquals($line->getContent(), "\n");
        $this->assertEquals((string)$line, "\n");
        $this->assertEquals($line->getStartLine(), 123);
        $this->assertEquals($line->getLinesCount(), 0);
        $this->assertEquals($line->getEndLine(), 123);
        $this->assertEquals($line->getLineAt( 0), 123);
        $this->assertEquals($line->getLineAt( 1), 123);
    }


    public function test_sep_2()
    {
        $line = new LdifSep("\r\n", 123);
        $this->assertEquals($line->getContent(), "\r\n");
        $this->assertEquals((string)$line, "\r\n");
        $this->assertEquals($line->getStartLine(), 123);
        $this->assertEquals($line->getLinesCount(), 0);
        $this->assertEquals($line->getEndLine(), 123);
        $this->assertEquals($line->getLineAt( 0), 123);
        $this->assertEquals($line->getLineAt( 1), 123);
    }
}

// vim: syntax=php sw=4 ts=4 et:
