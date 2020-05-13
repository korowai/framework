<?php
/**
 * @file tests/Records/AttrValRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\AttrValRecord;
use Korowai\Lib\Ldif\Records\AttrValRecordInterface;
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttrValRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, AttraValRecord::class);
    }

    public function test__implements__AttrValRecordInterface()
    {
        $this->assertImplementsInterface(AttrValRecordInterface::class, AttrValRecord::class);
    }

    public function test__uses__HasAttrValSpecs()
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, AttrValRecord::class);
    }

    public function test__construct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new AttrValRecord($snippet, "dc=example,dc=org", ['attrVal1']);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertSame("dc=example,dc=org", $record->getDn());
        $this->assertSame(['attrVal1'], $record->getAttrValSpecs());
    }

    public function test__setAttrValSpecs()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new AttrValRecord($snippet, "dc=example,dc=org", []);

        $this->assertSame($record, $record->setAttrValSpecs(['attrVal1']));
        $this->assertSame(['attrVal1'], $record->getAttrValSpecs());
    }

    public function test__acceptRecordVisitor()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new AttrValRecord($snippet, "dc=example,dc=org", []);

        $visitor->expects($this->once())
                ->method('visitAttrValRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
