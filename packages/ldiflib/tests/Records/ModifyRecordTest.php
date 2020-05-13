<?php
/**
 * @file tests/Records/ModifyRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\ModifyRecord;
use Korowai\Lib\Ldif\Records\ModifyRecordInterface;
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Exception\InvalidChangeTypeException;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModifyRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, AttraValRecord::class);
    }

    public function test__implements__ModifyRecordInterface()
    {
        $this->assertImplementsInterface(ModifyRecordInterface::class, ModifyRecord::class);
    }

    public static function construct__cases()
    {
        return [
            'w/o modSpecs' => [
                'args' => [
                    'cn=foo,dc=example,dc=org',
                ],
                'expect' => [
                    'dn' => 'cn=foo,dc=example,dc=org',
                    'modSpecs' => []
                ]
            ],
            'w/ modSpecs' => [
                'args' => [
                    'cn=foo,dc=example,dc=org',
                    [
                        'X'
                    ],
                ],
                'expect' => [
                    'dn' => 'cn=foo,dc=example,dc=org',
                    'modSpecs' => ['X']
                ]
            ]
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModifyRecord($snippet, ...$args);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public function test__setModSpecs()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModifyRecord($snippet, "cn=foo,dc=example,dc=org");

        $this->assertSame($record, $record->setModSpecs(['X']));
        $this->assertSame(['X'], $record->getModSpecs());
    }

    public function test__acceptRecordVisitor()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModifyRecord($snippet, "cn=foo,dc=example,dc=org", ['X']);

        $visitor->expects($this->once())
                ->method('visitModifyRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
