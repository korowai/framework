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

use Korowai\Lib\Ldif\Nodes\LdifModifyRecord;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\Nodes\AbstractChangeRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\InvalidChangeTypeException;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifModifyRecord
 */
final class LdifModifyRecordTest extends TestCase
{
    public function tets__extends__AbstractChangeRecord()
    {
        $this->assertExtendsClass(AbstractChangeRecord::class, LdifModifyRecord::class);
    }

    public function test__implements__LdifModifyRecordInterface() : void
    {
        $this->assertImplementsInterface(LdifModifyRecordInterface::class, LdifModifyRecord::class);
    }

    public function prov__construct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();
        return [
            '__construct(, "dc=example,dc=org")' => [
                'args' => [
                    'dc=example,dc=org',
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'modify',
                    'getModSpecs()' => [],
                    'getControls()' => [],
                    'getSnippet()' => null,
                ]
            ],
            '__construct("dc=example,dc=org", ["modSpecs" => ["X"], "controls" => ["Y"], "snippet" => $snippet]' => [
                'args' => [
                    'dc=example,dc=org',
                    [
                        "modSpecs" => ['X'],
                        "controls" => ['Y'],
                        "snippet" => $snippet
                    ],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'modify',
                    'getModSpecs()' => ['X'],
                    'getControls()' => ['Y'],
                    'getSnippet()' => $snippet,
                ]
            ]
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $record = new LdifModifyRecord(...$args);
        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public function test__getChangeType() : void
    {
        $record = new LdifModifyRecord("dc=example,dc=org");
        $this->assertSame("modify", $record->getChangeType());
    }

    public function test__setModSpecs() : void
    {
        $record = new LdifModifyRecord("dc=example,dc=org");

        $this->assertSame($record, $record->setModSpecs(['X']));
        $this->assertSame(['X'], $record->getModSpecs());
    }

    public function test__acceptRecordVisitor() : void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new LdifModifyRecord("dc=example,dc=org", ['X']);

        $visitor->expects($this->once())
                ->method('visitLdifModifyRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
