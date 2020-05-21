<?php
/**
 * @file tests/Records/AddRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\AddRecord;
use Korowai\Lib\Ldif\Records\AddRecordInterface;
use Korowai\Lib\Ldif\Records\AbstractChangeRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AddRecordTest extends TestCase
{
    public function tets__extends__AbstractChangeRecord()
    {
        $this->assertExtendsClass(AbstractChangeRecord::class, AttraValRecord::class);
    }

    public function test__implements__AddRecordInterface()
    {
        $this->assertImplementsInterface(AddRecordInterface::class, AddRecord::class);
    }

    public function test__uses__HasAttrValSpecs()
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, AddRecord::class);
    }

    public static function construct__cases()
    {
        return [
            '__construct($snippet, "dc=example,dc=org")' => [
                'args' => [
                    'dc=example,dc=org',
                ],
                'expect' => [
                    'dn' => 'dc=example,dc=org',
                    'changeType' => 'add',
                    'attrValSpecs' => [],
                    'controls' => [],
                ]
            ],
            '__construct($snippet, "dc=example,dc=org", ["attrValSpecs" => ["X"], "controls" => ["Y"]]' => [
                'args' => [
                    'dc=example,dc=org',
                    [
                        "attrValSpecs" => ['X'],
                        "controls" => ['Y'],
                    ],
                ],
                'expect' => [
                    'dn' => 'dc=example,dc=org',
                    'changeType' => 'add',
                    'attrValSpecs' => ['X'],
                    'controls' => ['Y'],
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

        $record = new AddRecord($snippet, ...$args);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public function test__setAttrValSpecs()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new AddRecord($snippet, "dc=example,dc=org", []);

        $this->assertSame($record, $record->setAttrValSpecs(['attrVal1']));
        $this->assertSame(['attrVal1'], $record->getAttrValSpecs());
    }

    public function test__acceptRecordVisitor()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new AddRecord($snippet, "dc=example,dc=org", []);

        $visitor->expects($this->once())
                ->method('visitAddRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
