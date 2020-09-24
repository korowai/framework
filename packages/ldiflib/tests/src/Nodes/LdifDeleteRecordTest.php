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

use Korowai\Lib\Ldif\Nodes\AbstractChangeRecord;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecord;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifDeleteRecord
 *
 * @internal
 */
final class LdifDeleteRecordTest extends TestCase
{
    public function tets__extends__AbstractChangeRecord()
    {
        $this->assertExtendsClass(AbstractChangeRecord::class, LdifDeleteRecord::class);
    }

    public function testImplementsLdifDeleteRecordInterface(): void
    {
        $this->assertImplementsInterface(LdifDeleteRecordInterface::class, LdifDeleteRecord::class);
    }

    public function provConstruct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();

        return [
            '__construct("dc=example,dc=org")' => [
                'args' => [
                    'dc=example,dc=org',
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'delete',
                    'getControls()' => [],
                    'getSnippet()' => null,
                ],
            ],
            '__construct("dc=example,dc=org", ["controls" => ["Y"], "snippet" => $snippet]' => [
                'args' => [
                    'dc=example,dc=org',
                    [
                        'controls' => ['Y'],
                        'snippet' => $snippet,
                    ],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'delete',
                    'getControls()' => ['Y'],
                    'getSnippet()' => $snippet,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $record = new LdifDeleteRecord(...$args);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $record);
    }

    public function testAcceptRecordVisitor(): void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
            ->getMockForAbstractClass()
        ;

        $record = new LdifDeleteRecord('dc=example,dc=org');

        $visitor->expects($this->once())
            ->method('visitLdifDeleteRecord')
            ->with($record)
            ->will($this->returnValue('ok'))
        ;

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
