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

use Korowai\Lib\Ldap\Core\LdapResultEntry;
use Korowai\Lib\Ldap\Core\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemTrait;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultEntry
 *
 * @internal
 */
final class LdapResultEntryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;
    use ResourceWrapperTestHelpersTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapResultEntryInterface(): void
    {
        $this->assertImplementsInterface(LdapResultEntryInterface::class, LdapResultEntry::class);
    }

    public function testUsesLdapResultItemTrait(): void
    {
        $this->assertUsesTrait(LdapResultItemTrait::class, LdapResultEntry::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetResource(): void
    {
        [$entry, $result] = $this->createLdapResultEntryAndMocks(1, 'ldap entry');
        $this->assertSame('ldap entry', $entry->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapResult(): void
    {
        [$entry, $result] = $this->createLdapResultEntryAndMocks(1);
        $this->assertSame($result, $entry->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapLink(): void
    {
        [$entry, $result, $link] = $this->createLdapResultEntryAndMocks();
        $this->assertSame($link, $entry->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__supportsResourceType(): array
    {
        return static::feedSupportsResourceType('ldap result entry');
    }

    /**
     * @dataProvider prov__supportsResourceType()
     *
     * @param mixed $expect
     */
    public function testSupportsResourceType(array $args, $expect): void
    {
        [$entry, $result] = $this->createLdapResultEntryAndMocks(1);

        $this->examineSupportsResourceType($entry, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__isValid(): array
    {
        return static::feedIsValid('ldap result entry');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isValid
     *
     * @param mixed $arg
     * @param mixed $return
     * @param mixed $expect
     */
    public function testIsValid($arg, $return, $expect): void
    {
        [$entry, $_] = $this->createLdapResultEntryAndMocks(1, $arg);
        $this->examineIsValid($entry, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_dn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_dn__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'dc=example,dc=org',
                'expect' => 'dc=example,dc=org',
            ],
            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_dn__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetDnWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('get_dn', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_attribute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__first_attribute__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'first',
                'expect' => 'first',
            ],
            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_attribute__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testFirstAttributeWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('first_attribute', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_attributes()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_attributes__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => ['1', '2'],
                'expect' => ['1', '2'],
            ],
            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_attributes__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetAttributesWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('get_attributes', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_values_len()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_values_len__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['foo'],
                'return' => [1, 2],
                'expect' => [1, 2],
            ],
            // #1
            [
                'args' => ['bar'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => ['#$#$'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_values_len__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetValuesLenWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('get_values_len', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_values()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_values__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['foo'],
                'return' => [1, 2],
                'expect' => [1, 2],
            ],
            // #1
            [
                'args' => ['bar'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => ['#$#$'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_values__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetValuesWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('get_values', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_attribute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_attribute__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'second',
                'expect' => 'second',
            ],

            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args' => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_attribute__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testNextAttributeWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('next_attribute', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_entry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_entry__withMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'ldap entry next',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultEntry::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap entry next'])
                ),
            ],

            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args' => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_entry__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testNextEntryWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('next_entry', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_item()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_entry__withMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testNextItemWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('next_item', $args, $return, $expect, 'ldap_next_entry');
    }

    private function createLdapResultEntryAndMocks(int $mocksLevel = 2, $resource = null): array
    {
        $link = $mocksLevel >= 2 ? $this->createLdapLinkMock() : null;
        $result = $this->createLdapResultMock($link);
        $entry = new LdapResultEntry($resource, $result);

        return array_slice([$entry, $result, $link], 0, max(2, 1 + $mocksLevel));
    }

    private function examineLdapMethod(string $method, array $args, $will, $expect, string $ldapFunction = null): void
    {
        [$entry, $result, $link] = $this->createLdapResultEntryAndMocks();

        if (null === $ldapFunction) {
            $ldapFunction = "ldap_{$method}";
        }

        $actual = $this->examineCallWithMockedLdapFunction(
            [$entry, $method],
            [$link, $entry],
            $args,
            $will,
            $expect,
            $ldapFunction
        );

        if ($actual instanceof LdapResultEntryWrapperInterface) {
            $this->assertSame($entry, $actual->getLdapResultEntry());
        }

        if ($actual instanceof LdapResultWrapperInterface) {
            $this->assertSame($result, $actual->getLdapResult());
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
