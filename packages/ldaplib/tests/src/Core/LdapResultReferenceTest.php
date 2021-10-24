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

use Korowai\Lib\Ldap\Core\LdapResultItemTrait;
use Korowai\Lib\Ldap\Core\LdapResultReference;
use Korowai\Lib\Ldap\Core\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultReference
 *
 * @internal
 */
final class LdapResultReferenceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;
    use ResourceWrapperTestHelpersTrait;
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;
    use ObjectPropertiesIdenticalToTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapResultReferenceInterface(): void
    {
        $this->assertImplementsInterface(LdapResultReferenceInterface::class, LdapResultReference::class);
    }

    public function testUsesLdapResultItemTrait(): void
    {
        $this->assertUsesTrait(LdapResultItemTrait::class, LdapResultReference::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetResource(): void
    {
        [$reference, $result] = $this->createLdapResultReferenceAndMocks(1, 'ldap reference');
        $this->assertSame('ldap reference', $reference->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapResult(): void
    {
        [$reference, $result] = $this->createLdapResultReferenceAndMocks(1);
        $this->assertSame($result, $reference->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapLink(): void
    {
        [$reference, $result, $link] = $this->createLdapResultReferenceAndMocks();
        $this->assertSame($link, $reference->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSupportsResourceType(): array
    {
        return static::feedSupportsResourceType('ldap result entry');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provSupportsResourceType
     *
     * @param mixed $expect
     */
    public function testSupportsResourceType(array $args, $expect): void
    {
        [$reference, $result] = $this->createLdapResultReferenceAndMocks(1);
        $this->examineSupportsResourceType($reference, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provIsValid(): array
    {
        return static::feedIsValid('ldap result entry');
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
        [$reference, $result] = $this->createLdapResultReferenceAndMocks(1, $arg);
        $this->examineIsValid($reference, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provNextReferenceWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'ldap entry next',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultReference::class),
                    static::objectPropertiesIdenticalTo(['getResource()' => 'ldap entry next'])
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
     * @dataProvider provNextReferenceWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testNextReferenceWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('next_reference', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // parse_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function provParseReferenceWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [&$rv1],
                'return' => true,
                'expect' => true,
                'values' => [['ldap://example.org/dc=subtree,dc=example,dc=org??sub']],
            ],

            // #1
            [
                'args' => [&$rv2],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],

            // #2
            [
                'args' => [&$rv3],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provParseReferenceWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testParseReferenceWithMockedBackend(array $args, $return, $expect, array $values): void
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
     * @dataProvider provNextReferenceWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testNextItemWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('next_item', $args, $return, $expect, 'ldap_next_reference');
    }

    private function createLdapResultReferenceAndMocks(int $mocksLevel = 2, $resource = null): array
    {
        $link = $mocksLevel >= 2 ? $this->createLdapLinkMock() : null;
        $result = $this->createLdapResultMock($link);
        $reference = new LdapResultReference($resource, $result);

        return array_slice([$reference, $result, $link], 0, max(2, 1 + $mocksLevel));
    }

    private function examineLdapMethod(string $method, array $args, $will, $expect, $ldapFunction = null): void
    {
        [$reference, $result, $link] = $this->createLdapResultReferenceAndMocks();

        if (null === $ldapFunction) {
            $ldapFunction = "ldap_{$method}";
        }

        $actual = $this->examineCallWithMockedLdapFunction(
            [$reference, $method],
            [$link, $reference],
            $args,
            $will,
            $expect,
            $ldapFunction
        );

        if ($actual instanceof LdapResultReferenceWrapperInterface) {
            $this->assertSame($reference, $actual->getLdapResultReference());
        }

        if ($actual instanceof LdapResultWrapperInterface) {
            $this->assertSame($result, $actual->getLdapResult());
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
