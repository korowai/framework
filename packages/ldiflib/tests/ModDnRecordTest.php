<?php
/**
 * @file tests/ModDnRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\ModDnRecord;
use Korowai\Lib\Ldif\ModDnRecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\AbstractRecord;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Exception\InvalidChangeTypeException;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModDnRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, AttraValRecord::class);
    }

    public function test__implements__ModDnRecordInterface()
    {
        $this->assertImplementsInterface(ModDnRecordInterface::class, ModDnRecord::class);
    }

    public static function construct__cases()
    {
        return [
            'w/o options' => [
                'args' => [
                    'cn=foo,dc=example,dc=org',
                    'cn=bar',
                ],
                'expect' => [
                    'getDn()' => 'cn=foo,dc=example,dc=org',
                    'getNewRdn()' => 'cn=bar',
                    'getChangeType()' => 'modrdn',
                    'getDeleteOldRdn()' => false,
                    'getNewSuperior()' => null,
                ]
            ],
            'w/ options' => [
                'args' => [
                    'cn=foo,dc=example,dc=org',
                    'cn=bar',
                    [
                        'changetype' => 'moddn',
                        'deleteoldrdn' => true,
                        'newsuperior' => 'dc=foobar,dc=com',
                    ],
                ],
                'expect' => [
                    'getDn()' => 'cn=foo,dc=example,dc=org',
                    'getNewRdn()' => 'cn=bar',
                    'getChangeType()' => 'moddn',
                    'getDeleteOldRdn()' => true,
                    'getNewSuperior()' => 'dc=foobar,dc=com',
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

        $record = new ModDnRecord($snippet, ...$args);

        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public static function setChangeType__cases()
    {
        return [
            ["moddn"],
            ["modrdn"]
        ];
    }

    /**
     * @dataProvider setChangeType__cases
     */
    public function test__setChangeType(string $changeType)
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModDnRecord($snippet, "cn=foo,dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setChangeType($changeType));
        $this->assertSame($changeType, $record->getChangeType());
    }

    public function test__setChangeType__invalidChangeType()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModDnRecord($snippet, "cn=foo,dc=example,dc=org", "cn=bar");

        $message = 'Argument 1 to '.ModDnRecord::class.'::setChangeType() must be one of "moddn" or "modrdn", '.
                   '"foo" given.';
        $this->expectException(InvalidChangeTypeException::class);
        $this->expectExceptionMessage($message);

        $record->setChangeType("foo");
    }

    public function test__setNewRdn()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModDnRecord($snippet, "cn=foo,dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setNewRdn("cn=gez"));
        $this->assertSame("cn=gez", $record->getNewRdn());
    }

    public function test__setDeleteOldRdn()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModDnRecord($snippet, "cn=foo,dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setDeleteOldRdn(true));
        $this->assertSame(true, $record->getDeleteOldRdn());

        $this->assertSame($record, $record->setDeleteOldRdn(false));
        $this->assertSame(false, $record->getDeleteOldRdn());
    }

    public function test__setNewSuperior()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModDnRecord($snippet, "cn=foo,dc=example,dc=org", "cn=bar");

        $this->assertSame($record, $record->setNewSuperior("dc=foobar,dc=com"));
        $this->assertSame("dc=foobar,dc=com", $record->getNewSuperior());

        $this->assertSame($record, $record->setNewSuperior(null));
        $this->assertSame(null, $record->getNewSuperior());
    }

    public function test__acceptRecordVisitor()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModDnRecord($snippet, "cn=foo,dc=example,dc=org", "cn=bar");

        $visitor->expects($this->once())
                ->method('visitModDnRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
