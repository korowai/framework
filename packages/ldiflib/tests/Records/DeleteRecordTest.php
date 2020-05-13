<?php
/**
 * @file tests/Records/DeleteRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\DeleteRecord;
use Korowai\Lib\Ldif\Records\DeleteRecordInterface;
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DeleteRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, AttraValRecord::class);
    }

    public function test__implements__DeleteRecordInterface()
    {
        $this->assertImplementsInterface(DeleteRecordInterface::class, DeleteRecord::class);
    }

    public function test__construct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new DeleteRecord($snippet, "dc=example,dc=org");

        $this->assertSame("delete", $record->getChangeType());
        $this->assertSame($snippet, $record->getSnippet());
        $this->assertSame("dc=example,dc=org", $record->getDn());
    }

    public function test__acceptRecordVisitor()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new DeleteRecord($snippet, "dc=example,dc=org");

        $visitor->expects($this->once())
                ->method('visitDeleteRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
