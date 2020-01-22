<?php
/**
 * @file Tests/Records/AbstractRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records\Tests;

use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\RangeInterface;
use Korowai\Lib\Ldif\Traits\DecoratesRangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRecordTest extends TestCase
{
    public function test__uses__DecoratesRangeInterface()
    {
        $uses = class_uses(AbstractRecord::class);
        $this->assertContains(DecoratesRangeInterface::class, $uses);
    }

    public function test__initAbstractRecord()
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->initAbstractRecord($range));
        $this->assertSame($range, $record->getRange());
    }
}

// vim: syntax=php sw=4 ts=4 et:
