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
use Korowai\Lib\Ldap\Core\LdapLink;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResult;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLink
 *
 * @internal
 */
final class LdapLinkTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;
    use ResourceWrapperTestHelpersTrait;

    //
    //
    //  TESTS
    //
    //

    public function testImplementesLdapLinkInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkInterface::class, LdapLink::class);
    }

    public function testUsesResourceWrapperTrait(): void
    {
        $this->assertUsesTrait(ResourceWrapperTrait::class, LdapLink::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // __construct()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provConstruct(): array
    {
        return [
            // #0
            [
                'args' => [null],
                'expect' => [
                    'resource' => null,
                ],
            ],

            // #1
            [
                'args' => ['ldap link'],
                'expect' => [
                    'resource' => 'ldap link',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     *
     * @param mixed $expect
     */
    public function testConstruct(array $args, $expect): void
    {
        $link = new LdapLink(...$args);
        $this->assertSame($expect['resource'], $link->getResource());
        $handler = $link->getErrorHandler();
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($handler, $link->getErrorHandler()); // always returns same instance
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSupportsResourceType(): array
    {
        return static::feedSupportsResourceType('ldap link');
    }

    /**
     * @dataProvider provSupportsResourceType()
     *
     * @param mixed $expect
     */
    public function testSupportsResourceType(array $args, $expect): void
    {
        $ldap = new LdapLink('foo');
        $this->examineSupportsResourceType($ldap, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provIsValid(): array
    {
        return static::feedIsValid('ldap link');
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
        $ldap = new LdapLink($arg);
        $this->examineIsValid($ldap, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // add()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provAddWithMockedBackend(): array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provAddWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testAddWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('add', $args, $return, $expect);
    }

    public static function provAddWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('add', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider provAddWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testAddWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('add', $resource, $args, $exception, $message);
    }

    public function testAddWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('add', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provBindWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => true,
                'expect' => true,
            ],
            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => ['cn=admin,dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],
            // #3
            [
                'args' => ['cn=admin,dc=acme,dc=com'],
                'return' => false,
                'expect' => false,
            ],
            // #4
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t'],
                'return' => true,
                'expect' => true,
            ],
            // #5
            [
                'args' => ['cn=admin,dc=acme,dc=com', '$3cr3t'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provBindWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testBindWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('bind', $args, $return, $expect);
    }

    public static function provBindWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('bind', ['string', 'string']);
    }

    /**
     * @dataProvider provBindWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testBindWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('bind', $resource, $args, $exception, $message);
    }

    public function testBindWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('bind', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // close()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provCloseWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => true,
                'expect' => true,
            ],
            // #1
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provCloseWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testCloseWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('close', $args, $return, $expect);
    }

    public static function provCloseWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('close', []);
    }

    /**
     * @dataProvider provCloseWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testCloseWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('close', $resource, $args, $exception, $message);
    }

    public function testCloseWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('close', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // compare()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provCompareWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org', 'attribute', 'value'],
                'return' => true,
                'expect' => true,
            ],
            // #1
            [
                'args' => ['dc=example,dc=org', 'attribute', 'value', ['serverctls']],
                'return' => true,
                'expect' => true,
            ],
            // #2
            [
                'args' => ['', '', ''],
                'return' => false,
                'expect' => false,
            ],
            // #3
            [
                'args' => ['', '', ''],
                'return' => -1,
                'expect' => -1,
            ],
            // #4
            [
                'args' => ['', '', ''],
                'return' => null,
                'expect' => -1,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provCompareWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testCompareWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('compare', $args, $return, $expect);
    }

    public static function provCompareWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('compare', ['string', 'string', 'string', 'array']);
    }

    /**
     * @dataProvider provCompareWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testCompareWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('compare', $resource, $args, $exception, $message);
    }

    public function testCompareWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('compare', ['', '', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // connect()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provConnectWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'ldap link 1',
                'expect' => ['getResource()' => 'ldap link 1'],
            ],
            // #1
            [
                'args' => ['ldapi:///'],
                'return' => 'ldap link 2',
                'expect' => ['getResource()' => 'ldap link 2'],
            ],
            // #2
            [
                'args' => ['localhost', 123],
                'return' => 'ldap link 3',
                'expect' => ['getResource()' => 'ldap link 3'],
            ],
            // #3
            [
                'args' => [null],
                'return' => 'ldap link 4',
                'expect' => ['getResource()' => 'ldap link 4'],
            ],
            // #4
            [
                'args' => [null, 123],
                'return' => 'ldap link 5',
                'expect' => ['getResource()' => 'ldap link 5'],
            ],
            // #5
            [
                'args' => ['ldapi:///', 123],
                'return' => 'ldap link 6',
                'expect' => ['getResource()' => 'ldap link 6'],
            ],
            // #6
            [
                'args' => ['#@#@'],
                'return' => false,
                'expect' => false,
            ],
            // #7
            [
                'args' => ['#@#@'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provConnectWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testConnectWithMockedBackend(array $args, $return, $expect): void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        if (2 === count($args) && null === $args[1]) {
            unset($ldapArgs[1]);
        }
        $this->getLdapFunctionMock('ldap_connect')
            ->expects($this->once())
            ->with(...$ldapArgs)
            ->willReturn($return)
        ;

        $ldap = LdapLink::connect(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapLink::class, $ldap);
            $this->assertSame($return, $ldap->getResource());
            $this->assertObjectHasPropertiesIdenticalTo($expect, $ldap);
        } else {
            $this->assertSame($expect, $ldap);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // delete()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provDeleteWithMockedBackend(): array
    {
        return static::feedDeleteWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provDeleteWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testDeleteWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('delete', $args, $return, $expect);
    }

    public static function provDeleteWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('delete', ['string', 'array']);
    }

    /**
     * @dataProvider provDeleteWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testDeleteWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('delete', $resource, $args, $exception, $message);
    }

    public function testDeleteWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('delete', ['']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // dn2ufn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provDn2ufnWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org'],
                'return' => 'example.com',
                'expect' => 'example.com',
            ],
            // #1
            [
                'args' => ['dc=acme,$#$#'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => ['$#$'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provDn2ufnWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testDn2ufnWithMockedBackend(array $args, $return, $expect): void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);

        $this->getLdapFunctionMock('ldap_dn2ufn')
            ->expects($this->once())
            ->with(...$ldapArgs)
            ->willReturn($return)
        ;

        $this->assertSame($expect, LdapLink::dn2ufn(...$args));
    }

    public static function provDn2ufnWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('dn2ufn', ['string'], false);
    }

    /**
     * @dataProvider provDn2ufnWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testDn2ufnWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('dn2ufn', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // err2str()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provErr2strWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [2],
                'return' => 'Protocol error',
                'expect' => 'Protocol error',
            ],
            // #1
            [
                'args' => [-321],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => [-10000000],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provErr2strWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testErr2strWithMockedBackend(array $args, $return, $expect): void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);

        $this->getLdapFunctionMock('ldap_err2str')
            ->expects($this->once())
            ->with(...$ldapArgs)
            ->willReturn($return)
        ;

        $this->assertSame($expect, LdapLink::err2str(...$args));
    }

    public static function provErr2strWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('err2str', ['int'], false);
    }

    /**
     * @dataProvider provErr2strWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testErr2strWithInvalidArgType(
        $resource,
        array $args,
        string $exception,
        string $message
    ): void {
        static::examineMethodWithInvalidArgType('err2str', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // errno()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provErrnoWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 123,
                'expect' => 123,
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
     * @dataProvider provErrnoWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testErrnoWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('errno', $args, $return, $expect);
    }

    public static function provErrnoWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('errno', []);
    }

    /**
     * @dataProvider provErrnoWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testErrnoWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('errno', $resource, $args, $exception, $message);
    }

    public function testErrnoWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('errno', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // error()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provErrorWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => 'Protocol error',
                'expect' => 'Protocol error',
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
     * @dataProvider provErrorWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testErrorWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('error', $args, $return, $expect);
    }

    public static function provErrorWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('error', []);
    }

    /**
     * @dataProvider provErrorWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testErrorWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('error', $resource, $args, $exception, $message);
    }

    public function testErrorWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('error', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // escape()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provEscapeWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org'],
                'return' => '\64\63\3d\65\78\61\6d\70\6c\65\2c\64\63\3d\6f\72\67',
                'expect' => '\64\63\3d\65\78\61\6d\70\6c\65\2c\64\63\3d\6f\72\67',
            ],
            // #1
            [
                'args' => ['dc=example,dc=org', '=,'],
                'return' => '\64\63=\65\78\61\6d\70\6c\65,\64\63=\6f\72\67',
                'expect' => '\64\63=\65\78\61\6d\70\6c\65,\64\63=\6f\72\67',
            ],
            // #2
            [
                'args' => ['dc=example,dc=org', '', LDAP_ESCAPE_DN],
                'return' => 'dc\3dexample\2cdc\3dorg',
                'expect' => 'dc\3dexample\2cdc\3dorg',
            ],
            // #3
            [
                'args' => ['', '', -123],
                'return' => false,
                'expect' => false,
            ],
            // #4
            [
                'args' => ['', '', -456],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provEscapeWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testEscapeWithMockedBackend(array $args, $return, $expect): void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        $this->getLdapFunctionMock('ldap_escape')
            ->expects($this->once())
            ->with(...$ldapArgs)
            ->willReturn($return)
        ;

        $this->assertSame($expect, LdapLink::escape(...$args));
    }

    public static function provEscapeWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('escape', ['string', 'string', 'int'], false);
    }

    /**
     * @dataProvider provEscapeWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testEscapeWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('escape', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // explode_dn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provExplodeDnWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org', 0],
                'return' => ['dc=example', 'dc=org', 'count' => 2],
                'expect' => ['dc=example', 'dc=org', 'count' => 2],
            ],
            // #1
            [
                'args' => ['***', -1],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args' => ['***', -2],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provExplodeDnWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testExplodeDnWithMockedBackend(array $args, $return, $expect): void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        $this->getLdapFunctionMock('ldap_explode_dn')
            ->expects($this->once())
            ->with(...$ldapArgs)
            ->willReturn($return)
        ;
        $this->assertSame($expect, LdapLink::explode_dn(...$args));
    }

    public static function provExplodeDnWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('explode_dn', ['string', 'int'], false);
    }

    /**
     * @dataProvider provExplodeDnWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testExplodeDnWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('explode_dn', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_option()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provGetOptionWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [1, &$rv1],
                'return' => true,
                'expect' => true,
                'values' => [123],
            ],
            // #1
            [
                'args' => [2, &$rv2],
                'return' => true,
                'expect' => true,
                'values' => ['foo'],
            ],
            // #2
            [
                'args' => [2, &$rv3],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],
            // #3
            [
                'args' => [-1, &$rv4],
                'return' => null,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provGetOptionWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testGetOptionWithMockedBackend(array $args, $return, $expect, array $values): void
    {
        $ldap = new LdapLink('ldap link');
        $ldapArgs = $this->makeArgsForLdapFunctionMock([$ldap], $args);

        $this->getLdapFunctionMock('ldap_get_option')
            ->expects($this->once())
            ->with(...$ldapArgs)
            ->willReturnCallback(new LdapGetOptionClosure($return, $values))
        ;

        $this->assertSame($expect, $ldap->get_option(...$args));
        if (count($args) > 1) {
            $this->assertSame($values[0] ?? null, $args[1]);
        }
    }

    public static function provGetOptionWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('get_option', ['int'], null, [&$var]);
    }

    /**
     * @dataProvider provGetOptionWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testGetOptionWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        static::examineMethodWithInvalidArgType('get_option', $resource, $args, $exception, $message);
    }

    public function testGetOptionWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('get_option', [0, &$var]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // list()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provListWithMockedBackend(): array
    {
        return static::feedSearchWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provListWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testListWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('list', $args, $return, $expect);
    }

    public static function provListWithInvalidArgType(): array
    {
        $types = ['string', 'string', 'array', 'int', 'int', 'int', 'int', 'array'];

        return static::feedFuncWithInvalidArgType('list', $types, false);
    }

    /**
     * @dataProvider provListWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testListWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('list', $resource, $args, $exception, $message);
    }

    public function testListWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('list', ['', '']);
    }

    public static function provModAddWithMockedBackend(): array
    {
        return static::feedModifyWithMockedBackend();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // mod_add()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider provModAddWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testModAddWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('mod_add', $args, $return, $expect);
    }

    public static function provModAddWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('mod_add', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider provModAddWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testModAddWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('mod_add', $resource, $args, $exception, $message);
    }

    public function testModAddWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('mod_add', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // mod_del()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provModDelWithMockedBackend(): array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provModDelWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testModDelWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('mod_del', $args, $return, $expect);
    }

    public static function provModDelWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('mod_del', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider provModDelWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testModDelWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('mod_del', $resource, $args, $exception, $message);
    }

    public function testModDelWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('mod_del', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // mod_replace()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provModReplaceWithMockedBackend(): array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provModReplaceWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testModReplaceWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('mod_replace', $args, $return, $expect);
    }

    public static function provModReplaceWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('mod_replace', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider provModReplaceWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testModReplaceWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('mod_replace', $resource, $args, $exception, $message);
    }

    public function testModReplaceWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('mod_replace', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // modify_batch()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provModifyBatchWithMockedBackend(): array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provModifyBatchWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testModifyBatchWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('modify_batch', $args, $return, $expect);
    }

    public static function provModifyBatchWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('modify_batch', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider provModifyBatchWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testModifyBatchWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('modify_batch', $resource, $args, $exception, $message);
    }

    public function testModifyBatchWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('modify_batch', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // modify()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provModifyWithMockedBackend(): array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provModifyWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testModifyWithMockedBackend(array $args, $return, $expect): void
    {
        static::examineLdapMethod('modify', $args, $return, $expect);
    }

    public static function provModifyWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('modify', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider provModifyWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testModifyWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('modify', $resource, $args, $exception, $message);
    }

    public function testModifyWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('modify', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // read()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provReadWithMockedBackend(): array
    {
        return static::feedSearchWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provReadWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testReadWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('read', $args, $return, $expect);
    }

    public static function provReadWithInvalidArgType(): array
    {
        $types = ['string', 'string', 'array', 'int', 'int', 'int', 'int', 'array'];

        return static::feedFuncWithInvalidArgType('read', $types, false);
    }

    /**
     * @dataProvider provReadWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testReadWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('read', $resource, $args, $exception, $message);
    }

    public function testReadWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('read', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // rename()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provRenameWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['cn=foo,dc=example,dc=org', 'cn=bar', '', false],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args' => ['cn=foo,dc=example,dc=org', 'cn=bar', '', false, ['serverctls']],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => ['', '', '', false],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args' => ['', '', '', false],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provRenameWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testRenameWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('rename', $args, $return, $expect);
    }

    public static function provRenameWithInvalidArgType(): array
    {
        $types = ['string', 'string', 'string', 'bool', 'array'];

        return static::feedFuncWithInvalidArgType('rename', $types);
    }

    /**
     * @dataProvider provRenameWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testRenameWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('rename', $resource, $args, $exception, $message);
    }

    public function testRenameWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('rename', ['', '', '', false]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // sasl_bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSaslBindWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args' => ['cn=admin,dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t'],
                'return' => true,
                'expect' => true,
            ],

            // #3
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech'],
                'return' => true,
                'expect' => true,
            ],

            // #4
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm'],
                'return' => true,
                'expect' => true,
            ],

            // #5
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id'],
                'return' => true,
                'expect' => true,
            ],

            // #6
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id'],
                'return' => true,
                'expect' => true,
            ],

            // #7
            [
                'args' => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id', 'props'],
                'return' => true,
                'expect' => true,
            ],

            // #8
            [
                'args' => ['$$'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provSaslBindWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSaslBindWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('sasl_bind', $args, $return, $expect);
    }

    public static function provSaslBindWithInvalidArgType(): array
    {
        $types = ['string', 'string', 'string', 'string', 'string', 'string', 'string'];

        return static::feedFuncWithInvalidArgType('sasl_bind', $types);
    }

    /**
     * @dataProvider provSaslBindWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testSaslBindWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('sasl_bind', $resource, $args, $exception, $message);
    }

    public function testSaslBindWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('sasl_bind', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // search()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSearchWithMockedBackend(): array
    {
        return static::feedSearchWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provSearchWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSearchWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('search', $args, $return, $expect);
    }

    public static function provSearchWithInvalidArgType(): array
    {
        $types = ['string', 'string', 'array', 'int', 'int', 'int', 'int', 'array'];

        return static::feedFuncWithInvalidArgType('search', $types, false);
    }

    /**
     * @dataProvider provSearchWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testSearchWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('search', $resource, $args, $exception, $message);
    }

    public function testSearchWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('search', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // set_option()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSetOptionWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => [1, 'a'],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args' => [2, ['x']],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args' => [0, ''],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args' => [-1, null],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provSetOptionWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSetOptionWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('set_option', $args, $return, $expect);
    }

    public static function provSetOptionWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('set_option', ['int'], false, ['x']);
    }

    /**
     * @dataProvider provSetOptionWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testSetOptionWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('set_option', $resource, $args, $exception, $message);
    }

    public function testSetOptionWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('set_option', [0, '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // set_rebind_proc()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provSetRebindProcWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['foo'],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args' => [''], // unsetting
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => ['inexistent'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provSetRebindProcWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSetRebindProcWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('set_rebind_proc', $args, $return, $expect);
    }

    public static function provSetRebindProcWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('set_rebind_proc', [], null, [function () {
        }]);
    }

    /**
     * @dataProvider provSetRebindProcWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testSetRebindProcWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('set_rebind_proc', $resource, $args, $exception, $message);
    }

    public function testSetRebindProcWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('set_rebind_proc', ['']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // start_tls()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provStartTlsWithMockedBackend(): array
    {
        return [
            // #1
            [
                'args' => [],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args' => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provStartTlsWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testStartTlsWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('start_tls', $args, $return, $expect);
    }

    public static function provUnbindWithMockedBackend(): array
    {
        return [
            // #1
            [
                'args' => [],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => [],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    public static function provStartTlsWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('start_tls', []);
    }

    /**
     * @dataProvider provStartTlsWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testStartTlsWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('start_tls', $resource, $args, $exception, $message);
    }

    public function testStartTlsWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('start_tls', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unbind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider provUnbindWithMockedBackend
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testUnbindWithMockedBackend(array $args, $return, $expect): void
    {
        $this->examineLdapMethod('unbind', $args, $return, $expect);
    }

    public static function provUnbindWithInvalidArgType(): array
    {
        return static::feedFuncWithInvalidArgType('unbind', []);
    }

    /**
     * @dataProvider provUnbindWithInvalidArgType
     *
     * @param mixed $resource
     */
    public function testUnbindWithInvalidArgType($resource, array $args, string $exception, string $message): void
    {
        $this->examineMethodWithInvalidArgType('unbind', $resource, $args, $exception, $message);
    }

    public function testUnbindWithInvalidLdapLink(): void
    {
        $this->examineMethodWithInvalidLdapLink('unbind', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unset()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function testUnsetDoesNotFreeTheResource(): void
    {
        $ldap = new LdapLink('ldap link');

        $this->getLdapFunctionMock('ldap_unbind')
            ->expects($this->never())
        ;

        $this->getLdapFunctionMock('ldap_close')
            ->expects($this->never())
        ;

        unset($ldap);
    }

    private function examineLdapMethod(string $method, array &$args, $will, $expect): void
    {
        $ldap = new LdapLink('ldap link');

        $actual = $this->examineCallWithMockedLdapFunction(
            [$ldap, $method],
            [$ldap],
            $args,
            $will,
            $expect,
            "ldap_{$method}"
        );

        if ($actual instanceof LdapLinkWrapperInterface) {
            $this->assertSame($ldap, $actual->getLdapLink());
        }
    }

    private function examineMethodWithInvalidArgType(
        string $method,
        $resource,
        array $args,
        string $exception,
        string $message
    ): void {
        $ldap = new LdapLink($resource);

        $this->expectException($exception);
        $this->expectExceptionMessageMatches($message);

        $ldap->{$method}(...$args);
    }

    private function examineMethodWithInvalidLdapLink(string $method, array $args): void
    {
        $qFun = preg_quote("ldap_{$method}", '/');
        $message = sprintf('/%s\(\): supplied resource is not a valid ldap link resource/', $qFun);

        $resource = ldap_connect('ldap://example.org');
        ldap_close($resource);

        $ldap = new LdapLink($resource);

        if (PHP_VERSION_ID >= 80000) {
            $this->expectException(\TypeError::class);
            $this->expectExceptionMessageMatches($message);
        } else {
            $this->expectWarning();
            $this->expectWarningMessageMatches($message);
        }
        call_user_func_array([$ldap, $method], $args);
    }

    private static function feedSearchWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org', 'objectclass=*'],
                'return' => 'ldap result 1',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 1'])
                ),
            ],

            // #1
            [
                'args' => ['dc=example,dc=org', 'objectclass=*', ['a', 'b']],
                'return' => 'ldap result 2',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 2'])
                ),
            ],

            // #2
            [
                'args' => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1],
                'return' => 'ldap result 3',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 3'])
                ),
            ],

            // #3
            [
                'args' => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123],
                'return' => 'ldap result 4',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 4'])
                ),
            ],

            // #4
            [
                'args' => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456],
                'return' => 'ldap result 5',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 5'])
                ),
            ],

            // #5
            [
                'args' => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456, 0],
                'return' => 'ldap result 6',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 6'])
                ),
            ],

            // #6
            [
                'args' => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456, 0, ['serverctls']],
                'return' => 'ldap result 6',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::objectHasPropertiesIdenticalTo(['getResource()' => 'ldap result 6'])
                ),
            ],

            // #7
            [
                'args' => ['', ''],
                'return' => false,
                'expect' => false,
            ],

            // #8
            [
                'args' => ['', ''],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    private static function feedModifyWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org', ['foo', 'bar']],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args' => ['dc=example,dc=org', ['foo', 'bar'], ['serverctls']],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args' => ['', []],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args' => ['', []],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    private static function feedDeleteWithMockedBackend(): array
    {
        return [
            // #0
            [
                'args' => ['dc=example,dc=org', ['foo', 'bar']],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args' => ['', []],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args' => ['', []],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    private static function makeMethodArgTypeErrorMessage(string $method, int $argno, string $type, string $given)
    {
        $qMet = preg_quote(LdapLink::class.'::'.$method.'()', '/');
        if (PHP_VERSION_ID < 80000) {
            return '/Argument '.$argno.' passed to '.$qMet.' must be of( the)? type '.$type.', '.$given.' given/';
        }

        return '/'.$qMet.': Argument #'.$argno.' \(\\$\w+\) must be of type '.$type.', '.$given.' given/';
    }

    private static function makeFunctionArgTypeErrorMessage(string $function, int $argno, string $type, string $given)
    {
        if (PHP_VERSION_ID < 80000) {
            return '/'.$function.'\(\) expects parameter 1 to be '.$type.', '.$given.' given/';
        }

        return '/'.$function.'\(\): Argument #'.$argno.' \(\\$\w+\) must be of type '.$type.', '.$given.' given/';
    }

    private static function feedFuncWithInvalidArgType(
        string $method,
        array $types,
        $invalidResource = null,
        array $tail = []
    ): array {
        $values = array_map(function ($t) {
            $repr = [
                'array' => [],
                'bool' => false,
                'callable' => function () {
                },
                'int' => 0,
                'null' => null,
                'string' => '',
            ];

            return $repr[$t];
        }, $types);

        $values = array_merge($values, $tail);

        $qMet = preg_quote(LdapLink::class.'::'.$method.'()', '/');
        $data = [];

        if (false !== $invalidResource) {
            $invalidResourceType = strtolower(gettype($invalidResource));
            if ('integer' === $invalidResourceType) {
                'int' === $invalidResourceType;
            }
            $data[] = [
                'resource' => $invalidResource,
                'args' => $values,
                'exception' => \TypeError::class,
                'message' => static::makeFunctionArgTypeErrorMessage('ldap_'.$method, 1, 'resource', $invalidResourceType),
            ];
        }

        for ($i = 0; $i < count($types); ++$i) {
            $type = $types[$i];
            $args = $values;
            $args[$i] = null;
            $data[] = [
                'resource' => \ldap_connect('ldap://example.org'),
                'args' => $args,
                'exception' => \TypeError::class,
                'message' => static::makeMethodArgTypeErrorMessage($method, $i + 1, $type, 'null'),
            ];
        }

        return $data;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
