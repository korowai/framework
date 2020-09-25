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

use Korowai\Lib\Ldif\Nodes\AbstractRecord;
use Korowai\Lib\Ldif\Nodes\LdifAttrValRecord;
use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifAttrValRecord
 *
 * @internal
 */
final class LdifAttrValRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, LdifAttrValRecord::class);
    }

    public function testImplementsLdifAttrValRecordInterface(): void
    {
        $this->assertImplementsInterface(LdifAttrValRecordInterface::class, LdifAttrValRecord::class);
    }

    public function testUsesHasAttrValSpecs(): void
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, LdifAttrValRecord::class);
    }

    public function provConstruct(): array
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();

        return [
            '__construct("dc=example,dc=org", ["X"])' => [
                'args' => [
                    'dc=example,dc=org',
                    ['X'],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getAttrValSpecs()' => ['X'],
                    'getSnippet()' => null,
                ],
            ],
            '__construct("dc=example,dc=org", ["X"], ["snippet" => $snippet])' => [
                'args' => [
                    'dc=example,dc=org',
                    ['X'],
                    ['snippet' => $snippet],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getAttrValSpecs()' => ['X'],
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
        $record = new LdifAttrValRecord(...$args);
        $this->assertObjectPropertiesIdenticalTo($expect, $record);
    }

    public function testSetAttrValSpecs(): void
    {
        $record = new LdifAttrValRecord('dc=example,dc=org', []);

        $this->assertSame($record, $record->setAttrValSpecs(['X']));
        $this->assertSame(['X'], $record->getAttrValSpecs());
    }

    public function testAcceptRecordVisitor(): void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
            ->getMockForAbstractClass()
        ;

        $record = new LdifAttrValRecord('dc=example,dc=org', []);

        $visitor->expects($this->once())
            ->method('visitLdifAttrValRecord')
            ->with($record)
            ->will($this->returnValue('ok'))
        ;

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
