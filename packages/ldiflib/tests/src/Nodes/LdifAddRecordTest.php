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
use Korowai\Lib\Ldif\Nodes\LdifAddRecord;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifAddRecord
 *
 * @internal
 */
final class LdifAddRecordTest extends TestCase
{
    public function tets__extends__AbstractChangeRecord()
    {
        $this->assertExtendsClass(AbstractChangeRecord::class, LdifAddRecord::class);
    }

    public function testImplementsLdifAddRecordInterface(): void
    {
        $this->assertImplementsInterface(LdifAddRecordInterface::class, LdifAddRecord::class);
    }

    public function testUsesHasAttrValSpecs(): void
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, LdifAddRecord::class);
    }

    public static function provConstruct()
    {
        return [
            '__construct("dc=example,dc=org")' => [
                'args' => [
                    'dc=example,dc=org',
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'add',
                    'getAttrValSpecs()' => [],
                    'getControls()' => [],
                    'getSnippet()' => null,
                ],
            ],
            '__construct("dc=example,dc=org", ["attrValSpecs" => ["X"], "controls" => ["Y"]]' => [
                'args' => [
                    'dc=example,dc=org',
                    [
                        'attrValSpecs' => ['X'],
                        'controls' => ['Y'],
                    ],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getChangeType()' => 'add',
                    'getAttrValSpecs()' => ['X'],
                    'getControls()' => ['Y'],
                    'getSnippet()' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $record = new LdifAddRecord(...$args);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $record);
    }

    public function testSetAttrValSpecs(): void
    {
        $record = new LdifAddRecord('dc=example,dc=org');
        $this->assertSame($record, $record->setAttrValSpecs(['X']));
        $this->assertSame(['X'], $record->getAttrValSpecs());
    }

    public function testAcceptRecordVisitor(): void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
            ->getMockForAbstractClass()
        ;

        $record = new LdifAddRecord('dc=example,dc=org');

        $visitor->expects($this->once())
            ->method('visitLdifAddRecord')
            ->with($record)
            ->will($this->returnValue('ok'))
        ;

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
