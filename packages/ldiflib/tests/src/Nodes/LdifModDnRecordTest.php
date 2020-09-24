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

use Korowai\Lib\Ldif\Nodes\LdifModDnRecord;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\AbstractRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\InvalidChangeTypeException;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifModDnRecord
 */
final class LdifModDnRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, LdifModDnRecord::class);
    }

    public function test__implements__LdifModDnRecordInterface() : void
    {
        $this->assertImplementsInterface(LdifModDnRecordInterface::class, LdifModDnRecord::class);
    }

    public function prov__construct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();
        return [
            '__construct("dc=example,dc=org", "cn=bar")' => [
                'args' => [
                    'dc=example,dc=org',
                    'cn=bar',
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getNewRdn()' => 'cn=bar',
                    'getChangeType()' => 'modrdn',
                    'getDeleteOldRdn()' => false,
                    'getNewSuperior()' => null,
                    'getControls()' => [],
                    'getSnippet()' => null,
                ]
            ],
            '__construct("dc=example,dc=org", "cn=bar", [...])' => [
                'args' => [
                    'dc=example,dc=org',
                    'cn=bar',
                    [
                        'changetype' => 'moddn',
                        'deleteoldrdn' => true,
                        'newsuperior' => 'dc=foobar,dc=com',
                        'controls' => ['Y'],
                        'snippet' => $snippet,
                    ],
                ],
                'expect' => [
                    'getDn()' => 'dc=example,dc=org',
                    'getNewRdn()' => 'cn=bar',
                    'getChangeType()' => 'moddn',
                    'getDeleteOldRdn()' => true,
                    'getNewSuperior()' => 'dc=foobar,dc=com',
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
        $record = new LdifModDnRecord(...$args);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $record);
    }

    public static function prov__setChangeType()
    {
        return [
            ["moddn"],
            ["modrdn"]
        ];
    }

    /**
     * @dataProvider prov__setChangeType
     */
    public function test__setChangeType(string $changeType) : void
    {
        $record = new LdifModDnRecord("dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setChangeType($changeType));
        $this->assertSame($changeType, $record->getChangeType());
    }

    public function test__setChangeType__invalidChangeType() : void
    {
        $record = new LdifModDnRecord("dc=example,dc=org", "cn=bar");

        $message = 'Argument 1 to '.LdifModDnRecord::class.'::setChangeType() must be one of "moddn" or "modrdn", '.
                   '"foo" given.';
        $this->expectException(InvalidChangeTypeException::class);
        $this->expectExceptionMessage($message);

        $record->setChangeType("foo");
    }

    public function test__setNewRdn() : void
    {
        $record = new LdifModDnRecord("dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setNewRdn("cn=gez"));
        $this->assertSame("cn=gez", $record->getNewRdn());
    }

    public function test__setDeleteOldRdn() : void
    {
        $record = new LdifModDnRecord("dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setDeleteOldRdn(true));
        $this->assertSame(true, $record->getDeleteOldRdn());

        $this->assertSame($record, $record->setDeleteOldRdn(false));
        $this->assertSame(false, $record->getDeleteOldRdn());
    }

    public function test__setNewSuperior() : void
    {
        $record = new LdifModDnRecord("dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setNewSuperior("dc=foobar,dc=com"));
        $this->assertSame("dc=foobar,dc=com", $record->getNewSuperior());

        $this->assertSame($record, $record->setNewSuperior(null));
        $this->assertSame(null, $record->getNewSuperior());
    }

    public function test__acceptRecordVisitor() : void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new LdifModDnRecord("dc=example,dc=org", "cn=bar");

        $visitor->expects($this->once())
                ->method('visitLdifModDnRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
