<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Core;

use Korowai\Lib\Basic\ResourceWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapResult;
use Korowai\Lib\Ldap\Core\LdapResultEntry;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultReference;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\UsesTraitTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResult
 *
 * @internal
 */
final class LdapResultTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;
    use CreateLdapLinkMockTrait;
    use ResourceWrapperTestHelpersTrait;
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;
    use ObjectPropertiesIdenticalToTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapResultInterface(): void
    {
        $this->assertImplementsInterface(LdapResultInterface::class, LdapResult::class);
    }

    public function testUsesResourceWrapperTrait(): void
    {
        $this->assertUsesTrait(ResourceWrapperTrait::class, LdapResult::class);
    }

    public function testUsesLdapLinkWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, LdapResult::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetResource(): void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);
        $this->assertSame('ldap result', $result->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapLink(): void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);
        $this->assertSame($ldap, $result->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSupportsResourceType(): array
    {
        return static::feedSupportsResourceType('ldap result');
    }

    /**
     * @dataProvider provSupportsResourceType()
     *
     * @param mixed $expect
     */
    public function testSupportsResourceType(array $args, $expect): void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('foo', $ldap);

        $this->examineSupportsResourceType($result, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provIsValid(): array
    {
        return static::feedIsValid('ldap result');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provIsValid
     *
     * @param mixed $arg
     * @param mixed $return
     * @param mixed $expect
     */
    public function testIsValid($arg, $return, $expect): void
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult($arg, $ldap);
        $this->examineIsValid($result, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // count_entries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provCountEntriesWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'will' => 123,
                'expect' => 123,
            ],
            // #1
            [
                'args' => [],
                'will' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'will' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provCountEntriesWithMockedBackend
     *
     * @param mixed $will
     * @param mixed $expect
     */
    public function testCountEntriesWithMockedBackend(array $args, $will, $expect): void
    {
        $this->examineCallWithMockedBackend('count_entries', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // count_references()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function testCountReferences(): void
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap result', $ldap);

        // FIXME: uncomment, once it's implemented
//        $this   ->getLdapFunctionMock("ldap_count_references")
//                ->expects($this->once())
//                ->with('ldap link', 'ldap result')
//                ->willReturn(333);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Not implemented');
        $result->count_references();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_entry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provFirstEntryWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'will' => 'first result entry',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultEntry::class),
                    static::objectPropertiesIdenticalTo(['getResource()' => 'first result entry'])
                ),
            ],
            // #1
            [
                'args' => [],
                'will' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'will' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provFirstEntryWithMockedBackend
     *
     * @param mixed $will
     * @param mixed $expect
     */
    public function testFirstEntryWithMockedBackend(array $args, $will, $expect): void
    {
        $this->examineCallWithMockedBackend('first_entry', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provFirstReferenceWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'will' => 'first result reference',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultReference::class),
                    static::objectPropertiesIdenticalTo(['getResource()' => 'first result reference'])
                ),
            ],
            // #1
            [
                'args' => [],
                'will' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'will' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provFirstReferenceWithMockedBackend
     *
     * @param mixed $will
     * @param mixed $expect
     */
    public function testFirstReferenceWithMockedBackend(array $args, $will, $expect): void
    {
        $this->examineCallWithMockedBackend('first_reference', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // free_result()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provFreeResultWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'will' => true,
                'expect' => true,
            ],
            // #1
            [
                'args' => [],
                'will' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'will' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provFreeResultWithMockedBackend
     *
     * @param mixed $will
     * @param mixed $expect
     */
    public function testFreeResultWithMockedBackend(array $args, $will, $expect): void
    {
        $this->examineCallWithMockedBackend('free_result', $args, $will, $expect, true);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_entries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provGetEntriesWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'will' => ['entry 1', 'entry 2'],
                'expect' => ['entry 1', 'entry 2'],
            ],
            // #1
            [
                'args' => [],
                'will' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'will' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provGetEntriesWithMockedBackend
     *
     * @param mixed $will
     * @param mixed $expect
     */
    public function testGetEntriesWithMockedBackend(array $args, $will, $expect): void
    {
        $this->examineCallWithMockedBackend('get_entries', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // parse_result()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function provParseResultWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [&$errcode1],
                'return' => true,
                'expect' => true,
                'values' => [123],
            ],

            // #1
            [
                'args' => [&$errcode2, &$matcheddn2],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org'],
            ],

            // #2
            [
                'args' => [&$errcode3, &$matcheddn3, &$errmsg3],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org', 'An error'],
            ],

            // #3
            [
                'args' => [&$errcode4, &$matcheddn4, &$errmsg4, &$referrals4],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org', 'An error', ['referrals']],
            ],

            // #4
            [
                'args' => [&$errcode4, &$matcheddn4, &$errmsg4, &$referrals4, &$serverctls],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org', 'An error', ['referrals'], ['serverctls']],
            ],

            // #5
            [
                'args' => [&$errcode5],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],

            // #6
            [
                'args' => [&$errcode6],
                'return' => null,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provParseResultWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testParseResultWithMockedBackend(array $args, $return, $expect, array $values): void
    {
        $will = $this->returnCallback(new LdapParseResultClosure($return, $values));
        $this->examineCallWithMockedBackend('parse_result', $args, $will, $expect);
        for ($i = 0; $i < 4; ++$i) {
            if (count($args) > $i) {
                $this->assertSame($values[$i] ?? null, $args[$i]);
            }
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // sort()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSortWithMockedBackend(): array
    {
        return [
            // #1
            [
                'args' => ['filter'],
                'will' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => ['***'],
                'will' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provSortWithMockedBackend
     *
     * @param mixed $will
     * @param mixed $expect
     */
    public function testSortWithMockedBackend(array $args, $will, $expect): void
    {
        $this->examineCallWithMockedBackend('sort', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unset()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function testUnsetDoesNotFreeTheResource(): void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);

        $this->getLdapFunctionMock('ldap_free_result')
            ->expects($this->never())
        ;

        unset($result);
    }

    private function examineCallWithMockedBackend(
        string $method,
        array &$args,
        $will,
        $expect,
        bool $bare = false
    ): void {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap link', $ldap);

        if ($bare) {
            $resources = [$result];
        } else {
            $resources = [$ldap, $result];
        }

        $actual = $this->examineCallWithMockedLdapFunction(
            [$result, $method],
            $resources,
            $args,
            $will,
            $expect,
            "ldap_{$method}"
        );

        if ($actual instanceof LdapResultWrapperInterface) {
            $this->assertSame($result, $actual->getLdapResult());
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
