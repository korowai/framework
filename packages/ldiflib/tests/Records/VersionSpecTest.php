<?php
/**
 * @file Tests/Records/VersionSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\VersionSpec;
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\Records\VersionSpecInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class VersionSpecTest extends TestCase
{
    public function test__implements__VersionSpecInterface()
    {
        $this->assertImplementsInterface(VersionSpecInterface::class, VersionSpec::class);
    }

    public function test__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, VersionSpec::class);
    }

    protected function getSnippet()
    {
        return $this->getMockBuilder(SnippetInterface::class)
                    ->getMockForAbstractClass();
    }

    public function test__construct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                      ->getMockForAbstractClass();
        $record = new VersionSpec($snippet, 12);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertSame(12, $record->getVersion());
    }

    public function test__acceptRecordVisitor()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                      ->getMockForAbstractClass();
        $record = new VersionSpec($snippet, 12);

        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();
        $visitor->expects($this->once())
                ->method('visitVersionSpec')
                ->with($record)
                ->willReturn('X');

        $this->assertSame('X', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
