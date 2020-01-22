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
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRecordTest extends TestCase
{
    public function test__uses__DecoratesSnippetInterface()
    {
        $uses = class_uses(AbstractRecord::class);
        $this->assertContains(DecoratesSnippetInterface::class, $uses);
    }

    public function test__initAbstractRecord()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                      ->getMockForAbstractClass();
        $record = $this->getMockBuilder(AbstractRecord::class)
                       ->getMockForAbstractClass();

        $this->assertSame($record, $record->initAbstractRecord($snippet));
        $this->assertSame($snippet, $record->getSnippet());
    }
}

// vim: syntax=php sw=4 ts=4 et:
