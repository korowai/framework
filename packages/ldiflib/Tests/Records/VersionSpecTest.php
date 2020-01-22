<?php
/**
 * @file Tests/VersionSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records\Tests;

use Korowai\Lib\Ldif\Records\VersionSpec;
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\CoupledRangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class VersionSpecTest extends TestCase
{
    public function test__implements__RecordInterface()
    {
        $interfaces = class_implements(VersionSpec::class);
        $this->assertContains(RecordInterface::class, $interfaces);
    }

    public function test__extends__AbstractRecord()
    {
        $parents = class_parents(VersionSpec::class);
        $this->assertContains(AbstractRecord::class, $parents);
    }

    protected function getRange()
    {
        return $this->getMockBuilder(CoupledRangeInterface::class)
                    ->getMockForAbstractClass();
    }

    public function test__construct()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $record = new VersionSpec($range, 12);

        $this->assertSame($range, $record->getRange());
        $this->assertSame(12, $record->getVersion());
    }

    public function test__acceptRecordVisitor()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $record = new VersionSpec($range, 12);

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
