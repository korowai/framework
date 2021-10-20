<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\ResultAttributeIterator;
use Korowai\Lib\Ldap\ResultEntry;
use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultEntryMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultEntry
 *
 * @internal
 */
final class ResultEntryTest extends TestCase
{
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultEntryMockTrait;
    use ExamineLdapLinkErrorHandlerTrait;
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsResultEntryInterface(): void
    {
        $this->assertImplementsInterface(ResultEntryInterface::class, ResultEntry::class);
    }

    public function testImplementsLdapResultEntryWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapResultEntryWrapperInterface::class, ResultEntry::class);
    }

    public function testUsesLdapResultEntryWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapResultEntryWrapperTrait::class, ResultEntry::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultEntry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapResultEntry(): void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);
        $this->assertSame($ldapEntry, $resultEntry->getLdapResultEntry());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultItem()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapResultItem(): void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);
        $this->assertSame($ldapEntry, $resultEntry->getLdapResultItem());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getDn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provGetDn(): array
    {
        return [
            // #0
            [
                'return' => 'dc=example,dc=org',
                'expect' => 'dc=example,dc=org',
            ],
            // #1
            [
                'return' => false,
                'expect' => '',
            ],
        ];
    }

    /**
     * @dataProvider provGetDn
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetDn($return, $expect): void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
            ->method('get_dn')
            ->willReturn($return)
        ;

        $this->assertSame($expect, $resultEntry->getDn());
    }

    public static function provGetDnWithTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provGetDnWithTriggerError
     */
    public function testGetDnWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineResultEntryMethodWithTriggerError('getDn', 'get_dn', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getAttributes()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provGetAttributes(): array
    {
        return [
            // #0
            [
                'return' => false,
                'expect' => [],
            ],
            // #1
            [
                'return' => [],
                'expect' => [],
            ],
            // #2
            [
                'return' => ['count' => 0],
                'expect' => [],
            ],
            // #3
            [
                'return' => [
                    'objectClass' => ['top', 'inetOrgPerson'],
                    'cn' => ['John Smith'],
                ],
                'expect' => [
                    'objectclass' => ['top', 'inetOrgPerson'],
                    'cn' => ['John Smith'],
                ],
            ],
            // #4
            [
                'return' => [
                    'objectClass' => [
                        'count' => 2,
                        'top',
                        'inetOrgPerson',
                    ],
                    'cn' => [
                        'count' => 1,
                        'John Smith',
                    ],
                    'count' => 2,
                ],
                'expect' => [
                    'objectclass' => ['top', 'inetOrgPerson'],
                    'cn' => ['John Smith'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provGetAttributes
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetAttributes($return, $expect): void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
            ->method('get_attributes')
            ->willReturn($return)
        ;

        $this->assertSame($expect, $resultEntry->getAttributes());
    }

    public static function provGetAttributesWithTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provGetAttributesWithTriggerError
     */
    public function testGetAttributesWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineResultEntryMethodWithTriggerError('getAttributes', 'get_attributes', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getAttributeIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetAttributeIterator(): void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->exactly(2))
            ->method('get_values')
            ->withConsecutive(['firstattribute'], ['secondattribute'])
            ->will($this->onConsecutiveCalls(['FIRST', 'count' => 1], ['SECOND', 'count' => 1]))
        ;

        $ldapEntry->expects($this->once())
            ->method('first_attribute')
            ->willReturn('FirstAttribute')
        ;

        $ldapEntry->expects($this->once())
            ->method('next_attribute')
            ->willReturn('SecondAttribute')
        ;

        $iterator = $resultEntry->getAttributeIterator();
        $this->assertInstanceOf(ResultAttributeIterator::class, $iterator);

        $this->assertSame($ldapEntry, $iterator->getLdapResultEntry());
        $this->assertEquals('firstattribute', $iterator->key());
        $this->assertEquals(['FIRST'], $iterator->current());

        $iterator->next();

        // single iterator instance per ResultEntry (dictated by ext-ldap implementation)
        $this->assertSame($iterator, $resultEntry->getAttributeIterator());
        $this->assertEquals('secondattribute', $iterator->key());
        $this->assertEquals(['SECOND'], $iterator->current());
    }

    public static function provGetAttributeIteratorWithTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provGetAttributeIteratorWithTriggerError
     */
    public function testGetAttributeIteratorWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineResultEntryMethodWithTriggerError('getAttributeIterator', 'first_attribute', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetIterator(): void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
            ->method('first_attribute')
            ->willReturn('first attribute')
        ;

        $this->assertSame($resultEntry->getIterator(), $resultEntry->getAttributeIterator());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // toEntry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testToEntry(): void
    {
        $ldapDn = 'uid=jsmith,ou=people,dc=korowai,dc=org';
        $ldapAttributes = [
            'count' => 3,
            'uid' => ['count' => 1, 'jsmith'],
            'firstName' => ['count' => 1, 'John'],
            'sn' => ['count' => 1, 'Smith'],
        ];
        $expectAttributes = [
            'uid' => ['jsmith'],
            'firstname' => ['John'],
            'sn' => ['Smith'],
        ];

        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
            ->method('get_dn')
            ->willReturn($ldapDn)
        ;
        $ldapEntry->expects($this->once())
            ->method('get_attributes')
            ->willReturn($ldapAttributes)
        ;

        $entry = $resultEntry->toEntry();

        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals($ldapDn, $entry->getDn());
        $this->assertSame($expectAttributes, $entry->getAttributes());
    }

    private function createResultEntryAndMocks(int $mocksDepth = 3): array
    {
        $link = $mocksDepth >= 3 ? $this->createLdapLinkMock() : null;
        $ldapResult = $mocksDepth >= 2 ? $this->createLdapResultMock($link) : null;
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult);
        $resultEntry = new ResultEntry($ldapEntry);

        return array_slice([$resultEntry, $ldapEntry, $ldapResult, $link], 0, max(2, 1 + $mocksDepth));
    }

    private function examineResultEntryMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        LdapTriggerErrorTestFixture $fixture
    ): void {
        [$resultEntry, $ldapEntry, $ldapResult, $link] = $this->createResultEntryAndMocks();

        $function = function () use ($resultEntry, $method, $args): void {
            $resultEntry->{$method}(...$args);
        };

        $subject = new LdapTriggerErrorTestSubject($ldapEntry, $backendMethod);
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }
}

// vim: syntax=php sw=4 ts=4 et:
