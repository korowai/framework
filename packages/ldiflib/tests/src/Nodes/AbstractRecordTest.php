<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\AbstractRecord;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasSnippet;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\AbstractRecord
 */
final class AbstractRecordTest extends TestCase
{
    public function test__implements__RecordInterface() : void
    {
        $this->assertImplementsInterface(RecordInterface::class, AbstractRecord::class);
    }

    public function test__uses__HasSnippet() : void
    {
        $this->assertUsesTrait(HasSnippet::class, AbstractRecord::class);
    }

    public function test__initAbstractRecord() : void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->initAbstractRecord("dc=example,dc=org", ['snippet' => $snippet]));
        $this->assertSame("dc=example,dc=org", $record->getDn());
        $this->assertSame($snippet, $record->getSnippet());

        $this->assertSame($record, $record->initAbstractRecord("dc=asme,dc=com"));
        $this->assertSame("dc=asme,dc=com", $record->getDn());
        $this->assertNull($record->getSnippet());
    }

    public function test__setDn() : void
    {
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->setDn("dc=example,dc=org"));
        $this->assertSame("dc=example,dc=org", $record->getDn());
    }

    public function test__setSnippet() : void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->setSnippet($snippet));
        $this->assertSame($snippet, $record->getSnippet());

        $this->assertSame($record, $record->setSnippet(null));
        $this->assertNull($record->getSnippet());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: