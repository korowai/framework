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

use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperTrait;
use Korowai\Lib\Ldap\Result;
use Korowai\Lib\Ldap\ResultEntry;
use Korowai\Lib\Ldap\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Ldap\ResultReference;
use Korowai\Lib\Ldap\ResultReferenceIteratorInterface;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultEntryMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultReferenceMockTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Result
 *
 * @internal
 */
final class ResultTest extends TestCase
{
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultEntryMockTrait;
    use CreateLdapResultReferenceMockTrait;
    use ExamineLdapLinkErrorHandlerTrait;
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;
    use ObjectPropertiesIdenticalToTrait;

    //
    //
    // TEST
    //
    //

    public function testImplementsResultInterface(): void
    {
        $this->assertImplementsInterface(ResultInterface::class, Result::class);
    }

    public function testImplementsLdapResultWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapResultWrapperInterface::class, Result::class);
    }

    public function testUsesLdapResultWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapResultWrapperTrait::class, Result::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapResult(): void
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);
        $this->assertSame($ldapResult, $result->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultEntries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provGetResultEntries(): array
    {
        return [
            // #0
            [
                'entries' => [],
            ],

            // #1
            [
                'entries' => ['first'],
            ],

            // #2
            [
                'entries' => ['first', 'second'],
            ],

            // #3
            [
                'entries' => ['first', 'second', 'third'],
            ],
        ];
    }

    /**
     * @dataProvider provGetResultEntries
     */
    public function testGetResultEntries(array $ldapEntries = []): void
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapEntries = array_map(function ($ldapEntry) use ($ldapResult) {
            return $this->createLdapResultEntryMock($ldapResult, $ldapEntry, ['next_item']);
        }, $ldapEntries);

        $first = $ldapEntries[0] ?? false;
        $ldapResult->expects($this->once())
            ->method('first_entry')
            ->willReturn($first)
        ;

        for ($i = 0; $i < count($ldapEntries); ++$i) {
            $curr = $ldapEntries[$i];
            $next = $ldapEntries[$i + 1] ?? false;
            $curr->expects($this->once())
                ->method('next_item')
                ->willReturn($next)
            ;
        }

        $constraints = array_map(function ($ldapEntry) {
            return $this->logicalAnd(
                $this->isInstanceOf(ResultEntry::class),
                $this->objectPropertiesIdenticalTo(['getLdapResultEntry()' => $ldapEntry]),
            );
        }, $ldapEntries);

        $entries = $result->getResultEntries();

        $this->assertCount(count($ldapEntries), $entries);

        for ($i = 0; $i < count($entries); ++$i) {
            $entry = $entries[$i];
            $expect = $constraints[$i];
            $this->assertThat($entry, $expect);
        }
    }

    public static function provGetResultEntriesWithTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provGetResultEntriesWithTriggerError
     */
    public function testGetResultEntriesWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineResultMethodWithTriggerError('getResultEntries', 'first_entry', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultReferences()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provGetResultReferences(): array
    {
        return [
            // #0
            [
                'references' => [],
            ],

            // #1
            [
                'references' => ['first'],
            ],

            // #2
            [
                'references' => ['first', 'second'],
            ],

            // #3
            [
                'references' => ['first', 'second', 'third'],
            ],
        ];
    }

    /**
     * @dataProvider provGetResultReferences
     */
    public function testGetResultReferences(array $ldapReferences = []): void
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapReferences = array_map(function ($ldapReference) use ($ldapResult) {
            return $this->createLdapResultReferenceMock($ldapResult, $ldapReference, ['next_item']);
        }, $ldapReferences);

        $first = $ldapReferences[0] ?? false;
        $ldapResult->expects($this->once())
            ->method('first_reference')
            ->willReturn($first)
        ;

        for ($i = 0; $i < count($ldapReferences); ++$i) {
            $curr = $ldapReferences[$i];
            $next = $ldapReferences[$i + 1] ?? false;
            $curr->expects($this->once())
                ->method('next_item')
                ->willReturn($next)
            ;
        }

        $constraints = array_map(function ($ldapReference) {
            return $this->logicalAnd(
                $this->isInstanceOf(ResultReference::class),
                $this->objectPropertiesIdenticalTo(['getLdapResultReference()' => $ldapReference]),
            );
        }, $ldapReferences);

        $references = $result->getResultReferences();

        $this->assertCount(count($ldapReferences), $references);

        for ($i = 0; $i < count($references); ++$i) {
            $reference = $references[$i];
            $expect = $constraints[$i];
            $this->assertThat($reference, $expect);
        }
    }

    public static function provGetResultReferencesWithTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provGetResultReferencesWithTriggerError
     */
    public function testGetResultReferencesWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineResultMethodWithTriggerError('getResultReferences', 'first_reference', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultEntryIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testGetResultEntryIterator(): void
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapResult->expects($this->once())
            ->method('first_entry')
            ->willReturn(false)
        ;

        $iterator = $result->getResultEntryIterator();

        $this->assertInstanceOf(ResultEntryIteratorInterface::class, $iterator);
        $this->assertNull($iterator->current());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultReferenceIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testGetResultReferenceIterator(): void
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapResult->expects($this->once())
            ->method('first_reference')
            ->willReturn(false)
        ;

        $iterator = $result->getResultReferenceIterator();

        $this->assertInstanceOf(ResultReferenceIteratorInterface::class, $iterator);
        $this->assertNull($iterator->current());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getEntries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testGetEntries(): void
    {
        $this->markTestIncomplete('Test not implemented yet');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testGetIterator(): void
    {
        $this->markTestIncomplete('Test not implemented yet');
    }

    private function createResultAndMocks(int $mocksDepth = 2): array
    {
        $link = $mocksDepth >= 2 ? $this->createLdapLinkMock() : null;
        $ldapResult = $this->createLdapResultMock($link);
        $result = new Result($ldapResult);

        return array_slice([$result, $ldapResult, $link], 0, max(2, 1 + $mocksDepth));
    }

    private function examineResultMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        LdapTriggerErrorTestFixture $fixture
    ): void {
        [$result, $ldapResult, $link] = $this->createResultAndMocks();

        $function = function () use ($result, $method, $args): void {
            $result->{$method}(...$args);
        };

        $subject = new LdapTriggerErrorTestSubject($ldapResult, $backendMethod);
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }
}

// vim: syntax=php sw=4 ts=4 et:
