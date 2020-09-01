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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultReferenceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use ResourceWrapperTestHelpersTrait;
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;

    private function examineLdapMethod(string $method, array $args, $will, $expect, $ldapFunction = null) : void
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = new LdapResultReference('ldap result entry', $result);

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

        if ($actual instanceof LdapResultReferenceWrapperInterface) {
            $this->assertSame($entry, $actual->getLdapResultReference());
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

    public function test__implements__LdapResultReferenceInterface()
    {
        $this->assertImplementsInterface(LdapResultReferenceInterface::class, LdapResultReference::class);
    }

    public function test__uses__LdapResultItemTrait()
    {
        $this->assertUsesTrait(LdapResultItemTrait::class, LdapResultReference::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getResource()
    {
        $result = $this->createLdapResultMock();
        $ref = new LdapResultReference('ldap reference', $result);
        $this->assertSame('ldap reference', $ref->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapResult()
    {
        $result = $this->createLdapResultMock(null);
        $ref = new LdapResultReference('ldap reference', $result);
        $this->assertSame($result, $ref->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapLink()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = $this->createLdapResultMock($ldap, null);
        $entry = new LdapResultReference('ldap entry', $result);
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
     * @runInSeparateProcess
     * @dataProvider prov__supportsResourceType
     */
    public function test__supportsResourceType(array $args, $expect)
    {
        $result = $this->createLdapResultMock(null, null);
        $reference = new LdapResultReference('foo', $result);
        $this->examineSupportsResourceType($reference, $args, $expect);
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
        $reference = new LdapResultReference($arg, $result);
        $this->examineIsValid($reference, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_reference__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'ldap entry next',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultReference::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap entry next'])
                ),
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
     * @dataProvider prov__next_reference__withMockedBackend
     */
    public function test__next_reference__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('next_reference', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // parse_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function prov__parse_reference__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [&$rv1],
                'return' => true,
                'expect' => true,
                'values' => [['ldap://example.org/dc=subtree,dc=example,dc=org??sub']],
            ],

            // #1
            [
                'args'   => [&$rv2],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],

            // #2
            [
                'args'   => [&$rv3],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }


    /**
     * @runInSeparateProcess
     * @dataProvider prov__parse_reference__withMockedBackend
     */
    public function test__parse_reference__withMockedBackend(array $args, $return, $expect, array $values)
    {
        $this->examineLdapMethod('parse_reference', $args, $return, $expect);
        if (count($args) > 1) {
            $this->assertSame($values[0] ?? null, $args[1]);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_item()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__next_reference__withMockedBackend
     */
    public function test__next_item__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineLdapMethod('next_item', $args, $return, $expect, 'ldap_next_reference');
    }
}

// vim: syntax=php sw=4 ts=4 et:
