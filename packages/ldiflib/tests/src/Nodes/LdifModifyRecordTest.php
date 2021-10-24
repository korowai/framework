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
use Korowai\Lib\Ldif\Nodes\LdifModifyRecord;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifModifyRecord
 *
 * @internal
 */
final class LdifModifyRecordTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use ObjectPropertiesIdenticalToTrait;

    public function tets__extends__AbstractChangeRecord()
    {
        $this->assertExtendsClass(AbstractChangeRecord::class, LdifModifyRecord::class);
    }

    public function testImplementsLdifModifyRecordInterface(): void
    {
        $this->assertImplementsInterface(LdifModifyRecordInterface::class, LdifModifyRecord::class);
    }

    public function provConstruct(): array
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
                ],
            ],
            '__construct("dc=example,dc=org", ["modSpecs" => ["X"], "controls" => ["Y"], "snippet" => $snippet]' => [
                'args' => [
                    'dc=example,dc=org',
                    [
                        'modSpecs' => ['X'],
                        'controls' => ['Y'],
                        'snippet' => $snippet,
                    ],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'modify',
                    'getModSpecs()' => ['X'],
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
        $record = new LdifModifyRecord(...$args);
        $this->assertObjectPropertiesIdenticalTo($expect, $record);
    }

    public function testGetChangeType(): void
    {
        $record = new LdifModifyRecord('dc=example,dc=org');
        $this->assertSame('modify', $record->getChangeType());
    }

    public function testSetModSpecs(): void
    {
        $record = new LdifModifyRecord('dc=example,dc=org');

        $this->assertSame($record, $record->setModSpecs(['X']));
        $this->assertSame(['X'], $record->getModSpecs());
    }

    public function testAcceptRecordVisitor(): void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
            ->getMockForAbstractClass()
        ;

        $record = new LdifModifyRecord('dc=example,dc=org', ['X']);

        $visitor->expects($this->once())
            ->method('visitLdifModifyRecord')
            ->with($record)
            ->will($this->returnValue('ok'))
        ;

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
