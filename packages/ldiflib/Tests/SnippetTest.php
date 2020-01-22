<?php
/**
 * @file Tests/SnippetTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\InputInterface;
use Korowai\Lib\Ldif\Traits\DecoratesLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SnippetTest extends TestCase
{
    public function test__implements__SnippetInterface()
    {
        $interfaces = class_implements(Snippet::class);
        $this->assertContains(SnippetInterface::class, $interfaces);
    }

    public function test__uses__DecoratesLocationInterface()
    {
        $uses = class_uses(Snippet::class);
        $this->assertContains(DecoratesLocationInterface::class, $uses);
    }

    public function test__construct()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $snippet = new Snippet($location, 12);

        $this->assertSame($location, $snippet->getLocation());
        $this->assertSame(12, $snippet->getLength());
    }

    public function test__init()
    {
        $location1 = $this->getMockBuilder(LocationInterface::class)
                          ->getMockForAbstractClass();
        $location2 = $this->getMockBuilder(LocationInterface::class)
                          ->getMockForAbstractClass();

        $snippet = new Snippet($location1, 12);

        $this->assertSame($snippet, $snippet->init($location2, 24));

        $this->assertSame($location2, $snippet->getLocation());
        $this->assertSame(24, $snippet->getLength());
    }

    public function test__setLength()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $snippet = new Snippet($location, 12);

        $this->assertSame($snippet, $snippet->setLength(24));

        $this->assertSame($location, $snippet->getLocation());
        $this->assertSame(24, $snippet->getLength());
    }

    public function test__getEndOffset()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $snippet = new Snippet($location, 12);

        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(7);

        $this->assertSame(12 + 7, $snippet->getEndOffset());
    }

    public function test__getSourceLength()
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();

        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4 (2)
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);
        $location->expects($this->once())
                 ->method('getSourceOffset')
                 ->with()
                 ->willReturn(2);

        // end: 10 (7)
        $input->expects($this->once())
              ->method('getSourceOffset')
              ->with(4 + 6)
              ->willReturn(7);

        $snippet = new Snippet($location, 6);

        $this->assertSame(7 - 2, $snippet->getSourceLength());
    }

    public function test__getSourceEndOffset()
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);

        // end: 10 (7)
        $input->expects($this->once())
              ->method('getSourceOffset')
              ->with(4 + 6)
              ->willReturn(7);

        $snippet = new Snippet($location, 6);

        $this->assertSame(7, $snippet->getSourceEndOffset());
    }

    public function encodingCases()
    {
        return [ [], ['U'] ];
    }

    /**
     * @dataProvider encodingCases
     */
    public function test__getSourceCharLength(...$enc)
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4 (3)
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);
        $location->expects($this->once())
                 ->method('getSourceCharOffset')
                 ->with(...$enc)
                 ->willReturn(3);

        // end: 10 (8)
        $input->expects($this->once())
              ->method('getSourceCharOffset')
              ->with(4 + 6, ...$enc)
              ->willReturn(8);

        $snippet = new Snippet($location, 6);

        $this->assertSame(8 - 3, $snippet->getSourceCharLength(...$enc));
    }

    /**
     * @dataProvider encodingCases
     */
    public function test__getSourceCharEndOffset(...$enc)
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);

        // end: 10 (9)
        $input->expects($this->once())
              ->method('getSourceCharOffset')
              ->with(4 + 6, ...$enc)
              ->willReturn(9);

        $snippet = new Snippet($location, 6);

        $this->assertSame(9, $snippet->getSourceCharEndOffset(...$enc));
    }
}

// vim: syntax=php sw=4 ts=4 et:
