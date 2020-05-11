<?php
/**
 * @file tests/Records/AbstractRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRecordTest extends TestCase
{
    public function test__implements__RecordInterface()
    {
        $this->assertImplementsInterface(RecordInterface::class, AbstractRecord::class);
    }

    public function test__uses__DecoratesSnippetInterface()
    {
        $this->assertUsesTrait(DecoratesSnippetInterface::class, AbstractRecord::class);
    }

    public function test__initAbstractRecord()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->initAbstractRecord($snippet, "dc=example,dc=org"));
        $this->assertSame($snippet, $record->getSnippet());
        $this->assertSame("dc=example,dc=org", $record->getDn());
    }

    public function test__setSnippet()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->setSnippet($snippet));
        $this->assertSame($snippet, $record->getSnippet());
    }

    public function test__setDn()
    {
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->setDn("dc=example,dc=org"));
        $this->assertSame("dc=example,dc=org", $record->getDn());
    }
}

// vim: syntax=php sw=4 ts=4 et:
