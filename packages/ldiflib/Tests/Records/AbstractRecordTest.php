<?php
/**
 * @file Tests/AbstractRecordTest.php
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
use Korowai\Lib\Ldif\CoupledRangeInterface;
use Korowai\Lib\Ldif\Traits\DecoratesCoupledRangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRecordTest extends TestCase
{
    public function test__implements__RecordInterface()
    {
        $interfaces = class_implements(AbstractRecord::class);
        $this->assertContains(RecordInterface::class, $interfaces);
    }

    public function test__uses__DecoratesCoupledRangeInterface()
    {
        $uses = class_uses(AbstractRecord::class);
        $this->assertContains(DecoratesCoupledRangeInterface::class, $uses);
    }

    public function test__initAbstractRecord()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->initAbstractRecord($range));
        $this->assertSame($range, $record->getRange());
    }
}

// vim: syntax=php sw=4 ts=4 et:
