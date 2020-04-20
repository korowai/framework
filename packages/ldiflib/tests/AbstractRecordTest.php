<?php
/**
 * @file tests/AbstractRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\AbstractRecord;
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

        $this->assertSame($record, $record->initAbstractRecord($snippet, "DN"));
        $this->assertSame($snippet, $record->getSnippet());
        $this->assertSame("DN", $record->getDn());
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

        $this->assertSame($record, $record->setDn("DN"));
        $this->assertSame("DN", $record->getDn());
    }
}

// vim: syntax=php sw=4 ts=4 et:
