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

use Korowai\Lib\Ldif\Nodes\LdifAddRecord;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\AbstractChangeRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifAddRecord
 */
final class LdifAddRecordTest extends TestCase
{
    public function tets__extends__AbstractChangeRecord()
    {
        $this->assertExtendsClass(AbstractChangeRecord::class, LdifAddRecord::class);
    }

    public function test__implements__LdifAddRecordInterface() : void
    {
        $this->assertImplementsInterface(LdifAddRecordInterface::class, LdifAddRecord::class);
    }

    public function test__uses__HasAttrValSpecs() : void
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, LdifAddRecord::class);
    }

    public static function construct__cases()
    {
        return [
            '__construct("dc=example,dc=org")' => [
                'args' => [
                    'dc=example,dc=org',
                ],
                'expect' => [
                    'dn' => 'dc=example,dc=org',
                    'changeType' => 'add',
                    'attrValSpecs' => [],
                    'controls' => [],
                    'snippet' => null,
                ]
            ],
            '__construct("dc=example,dc=org", ["attrValSpecs" => ["X"], "controls" => ["Y"]]' => [
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
                    'snippet' => null
                ]
            ]
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect) : void
    {
        $record = new LdifAddRecord(...$args);
        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public function test__setAttrValSpecs() : void
    {
        $record = new LdifAddRecord("dc=example,dc=org");
        $this->assertSame($record, $record->setAttrValSpecs(['X']));
        $this->assertSame(['X'], $record->getAttrValSpecs());
    }

    public function test__acceptRecordVisitor() : void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new LdifAddRecord("dc=example,dc=org");

        $visitor->expects($this->once())
                ->method('visitAddRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: