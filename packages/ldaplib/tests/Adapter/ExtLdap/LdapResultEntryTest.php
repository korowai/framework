<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultEntryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use ResourceWrapperTestHelpersTrait;
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;

    private function examineLdapMethod(string $method, array $args, $will, $expect, string $ldapFunction = null) : void
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = new LdapResultEntry('ldap result entry', $result);

        if ($ldapFunction === null) {
            $ldapFunction = "ldap_$method";
        }

        $actual = $this->examineCallWithMockedLdapFunction(
            [$entry, $method],
            [$ldap, $entry],
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

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapResultEntryInterface()
    {
        $this->assertImplementsInterface(LdapResultEntryInterface::class, LdapResultEntry::class);
    }

    public function test__uses__LdapResultItemTrait()
    {
        $this->assertUsesTrait(LdapResultItemTrait::class, LdapResultEntry::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getResource()
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry('ldap entry', $result);
        $this->assertSame('ldap entry', $entry->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapResult()
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry('ldap entry', $result);
        $this->assertSame($result, $entry->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapLink()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = $this->createLdapResultMock($ldap, null);
        $entry = new LdapResultEntry('ldap entry', $result);
        $this->assertSame($ldap, $entry->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__supportsResourceType()
    {
        return static::feedSupportsResourceType('ldap result entry');
    }

    /**
     * @dataProvider prov__supportsResourceType()
     */
    public function test__supportsResourceType(array $args, $expect) : void
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry('foo', $result);

        $this->examineSupportsResourceType($entry, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__isValid()
    {
        return static::feedIsValid('ldap result entry');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isValid
     */
    public function test__isValid($arg, $return, $expect)
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry($arg, $result);
        $this->examineIsValid($entry, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_dn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_dn__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'dc=example,dc=org',
                'expect' => 'dc=example,dc=org',
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_dn__withMockedBackend
     */
    public function test__get_dn__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('get_dn', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_attribute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__first_attribute__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'first',
                'expect' => 'first',
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_attribute__withMockedBackend
     */
    public function test__first_attribute__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('first_attribute', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_attributes()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_attributes__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => ['1', '2'],
                'expect' => ['1', '2']
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_attributes__withMockedBackend
     */
    public function test__get_attributes__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('get_attributes', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_values_len()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_values_len__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => ['foo'],
                'return' => [1, 2],
                'expect' => [1, 2],
            ],
            // #1
            [
                'args'   => ['bar'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['#$#$'],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_values_len__withMockedBackend
     */
    public function test__get_values_len__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('get_values_len', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_values()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_values__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => ['foo'],
                'return' => [1, 2],
                'expect' => [1, 2],
            ],
            // #1
            [
                'args'   => ['bar'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['#$#$'],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_values__withMockedBackend
     */
    public function test__get_values__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('get_values', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_attribute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_attribute__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'second',
                'expect' => 'second'
            ],

            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_attribute__withMockedBackend
     */
    public function test__next_attribute__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('next_attribute', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_entry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_entry__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'ldap entry next',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultEntry::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap entry next'])
                )
            ],

            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_entry__withMockedBackend
     */
    public function test__next_entry__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('next_entry', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_item()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_entry__withMockedBackend
     */
    public function test__next_item__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('next_item', $args, $return, $expect, 'ldap_next_entry');
    }
}

// vim: syntax=php sw=4 ts=4 et:
