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

use Korowai\Lib\Ldif\InvalidChangeTypeException;
use Korowai\Lib\Ldif\Nodes\AbstractRecord;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecord;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\LdifModDnRecord
 *
 * @internal
 */
final class LdifModDnRecordTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use ObjectPropertiesIdenticalToTrait;

    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, LdifModDnRecord::class);
    }

    public function testImplementsLdifModDnRecordInterface(): void
    {
        $this->assertImplementsInterface(LdifModDnRecordInterface::class, LdifModDnRecord::class);
    }

    public function provConstruct(): array
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
                ],
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
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $record = new LdifModDnRecord(...$args);
        $this->assertObjectPropertiesIdenticalTo($expect, $record);
    }

    public static function provSetChangeType(): array
    {
        return [
            ['moddn'],
            ['modrdn'],
        ];
    }

    /**
     * @dataProvider provSetChangeType
     */
    public function testSetChangeType(string $changeType): void
    {
        $record = new LdifModDnRecord('dc=example,dc=org', 'cn=bar');

        $this->assertSame($record, $record->setChangeType($changeType));
        $this->assertSame($changeType, $record->getChangeType());
    }

    public function testSetChangeTypeInvalidChangeType(): void
    {
        $record = new LdifModDnRecord('dc=example,dc=org', 'cn=bar');

        $message = 'Argument 1 to '.LdifModDnRecord::class.'::setChangeType() must be one of "moddn" or "modrdn", '.
                   '"foo" given.';
        $this->expectException(InvalidChangeTypeException::class);
        $this->expectExceptionMessage($message);

        $record->setChangeType('foo');
    }

    public function testSetNewRdn(): void
    {
        $record = new LdifModDnRecord('dc=example,dc=org', 'cn=bar');

        $this->assertSame($record, $record->setNewRdn('cn=gez'));
        $this->assertSame('cn=gez', $record->getNewRdn());
    }

    public function testSetDeleteOldRdn(): void
    {
        $record = new LdifModDnRecord('dc=example,dc=org', 'cn=bar');

        $this->assertSame($record, $record->setDeleteOldRdn(true));
        $this->assertSame(true, $record->getDeleteOldRdn());

        $this->assertSame($record, $record->setDeleteOldRdn(false));
        $this->assertSame(false, $record->getDeleteOldRdn());
    }

    public function testSetNewSuperior(): void
    {
        $record = new LdifModDnRecord('dc=example,dc=org', 'cn=bar');

        $this->assertSame($record, $record->setNewSuperior('dc=foobar,dc=com'));
        $this->assertSame('dc=foobar,dc=com', $record->getNewSuperior());

        $this->assertSame($record, $record->setNewSuperior(null));
        $this->assertSame(null, $record->getNewSuperior());
    }

    public function testAcceptRecordVisitor(): void
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
            ->getMockForAbstractClass()
        ;

        $record = new LdifModDnRecord('dc=example,dc=org', 'cn=bar');

        $visitor->expects($this->once())
            ->method('visitLdifModDnRecord')
            ->with($record)
            ->will($this->returnValue('ok'))
        ;

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
