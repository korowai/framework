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
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Basic\ResourceWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResult;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Stub\Stub;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink
 */
final class LdapLinkTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;
    use ResourceWrapperTestHelpersTrait;

    private function examineLdapMethod(string $method, array &$args, $will, $expect) : void
    {
        $ldap = new LdapLink('ldap link');

        $actual = $this->examineCallWithMockedLdapFunction(
            [$ldap, $method],
            [$ldap],
            $args,
            $will,
            $expect,
            "ldap_$method"
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
    ) : void {
        $ldap = new LdapLink($resource);

        $this->expectException($exception);
        $this->expectExceptionMessageMatches($message);

        $ldap->{$method}(...$args);
    }

    private function examineMethodWithInvalidLdapLink(string $method, array $args) : void
    {
        $qFun = preg_quote("ldap_$method", '/');
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

    private static function feedSearchWithMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*'],
                'return' => 'ldap result 1',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 1'])
                ),
            ],

            // #1
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b']],
                'return' => 'ldap result 2',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 2'])
                ),
            ],

            // #2
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1],
                'return' => 'ldap result 3',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 3'])
                ),
            ],

            // #3
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123],
                'return' => 'ldap result 4',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 4'])
                ),
            ],

            // #4
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456],
                'return' => 'ldap result 5',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 5'])
                ),
            ],

            // #5
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456, 0],
                'return' => 'ldap result 6',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 6'])
                ),
            ],

            // #6
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456, 0, ['serverctls']],
                'return' => 'ldap result 6',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResult::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'ldap result 6'])
                ),
            ],

            // #7
            [
                'args'   => ['', ''],
                'return' => false,
                'expect' => false,
            ],

            // #8
            [
                'args'   => ['', ''],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    private static function feedModifyWithMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', ['foo', 'bar']],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => ['dc=example,dc=org', ['foo', 'bar'], ['serverctls']],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['', []],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => ['', []],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    private static function feedDeleteWithMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', ['foo', 'bar']],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => ['', []],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args'   => ['', []],
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
        } else {
            return '/'.$qMet.': Argument #'.$argno.' \(\\$\w+\) must be of type '.$type.', '.$given.' given/';
        }
    }

    private static function makeFunctionArgTypeErrorMessage(string $function, int $argno, string $type, string $given)
    {
        if (PHP_VERSION_ID < 80000) {
            return '/'.$function.'\(\) expects parameter 1 to be '.$type.', '.$given.' given/';
        } else {
            return '/'.$function.'\(\): Argument #'.$argno.' \(\\$\w+\) must be of type '.$type.', '.$given.' given/';
        }
    }

    private static function feedFuncWithInvalidArgType(
        string $method,
        array $types,
        $invalidResource = null,
        array $tail = []
    ) : array {
        $values = array_map(function ($t) {
            $repr = [
                'array'    => [],
                'bool'     => false,
                'callable' => function () {
                },
                'int'      => 0,
                'null'     => null,
                'string'   => '',
            ];
            return $repr[$t];
        }, $types);

        $values = array_merge($values, $tail);

        $qMet = preg_quote(LdapLink::class.'::'.$method.'()', '/');
        $data = [];

        if ($invalidResource !== false) {
            $invalidResourceType = strtolower(gettype($invalidResource));
            if ($invalidResourceType === 'integer') {
                $invalidResourceType === 'int';
            }
            $data[] = [
                'resource'   => $invalidResource,
                'args'       => $values,
                'exception'  => \TypeError::class,
                'message'    => static::makeFunctionArgTypeErrorMessage('ldap_'.$method, 1, 'resource', $invalidResourceType),
            ];
        }

        for ($i = 0; $i < count($types); $i++) {
            $type = $types[$i];
            $args = $values;
            $args[$i] = null;
            $data[] = [
                'resource'  => \ldap_connect('ldap://example.org'),
                'args'      => $args,
                'exception' => \TypeError::class,
                'message'   => static::makeMethodArgTypeErrorMessage($method, $i+1, $type, 'null'),
            ];
        }

        return $data;
    }

    //
    //
    //  TESTS
    //
    //

    public function test__implementes__LdapLinkInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkInterface::class, LdapLink::class);
    }

    public function test__uses__ResourceWrapperTrait() : void
    {
        $this->assertUsesTrait(ResourceWrapperTrait::class, LdapLink::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getResource() : array
    {
        return [
            // #0
            [
                'args'   => [null],
                'expect' => null,
            ],

            // #1
            [
                'args'   => ['ldap link'],
                'expect' => 'ldap link',
            ],
        ];
    }

    /**
     * @dataProvider prov__getResource
     */
    public function test__getResource(array $args, $expect) : void
    {
        $link = new LdapLink(...$args);
        $this->assertSame($expect, $link->getResource());
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__supportsResourceType() : array
    {
        return static::feedSupportsResourceType('ldap link');
    }

    /**
     * @dataProvider prov__supportsResourceType()
     */
    public function test__supportsResourceType(array $args, $expect) : void
    {
        $ldap = new LdapLink('foo');
        $this->examineSupportsResourceType($ldap, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__isValid() : array
    {
        return static::feedIsValid('ldap link');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isValid
     */
    public function test__isValid($arg, $return, $expect) : void
    {
        $ldap = new LdapLink($arg);
        $this->examineIsValid($ldap, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // add()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__add__withMockedBackend() : array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__add__withMockedBackend
     */
    public function test__add__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('add', $args, $return, $expect);
    }

    public static function prov__add__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('add', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider prov__add__withInvalidArgType
     */
    public function test__add__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('add', $resource, $args, $exception, $message);
    }

    public function test__add__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('add', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__bind__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => true,
                'expect' => true
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false
            ],
            // #2
            [
                'args'   => ['cn=admin,dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],
            // #3
            [
                'args'   => ['cn=admin,dc=acme,dc=com'],
                'return' => false,
                'expect' => false,
            ],
            // #4
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t'],
                'return' => true,
                'expect' => true,
            ],
            // #5
            [
                'args'   => ['cn=admin,dc=acme,dc=com', '$3cr3t'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__bind__withMockedBackend
     */
    public function test__bind__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('bind', $args, $return, $expect);
    }

    public static function prov__bind__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('bind', ['string', 'string']);
    }

    /**
     * @dataProvider prov__bind__withInvalidArgType
     */
    public function test__bind__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('bind', $resource, $args, $exception, $message);
    }

    public function test__bind__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('bind', ['', '']);
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // close()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__close__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => true,
                'expect' => true
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__close__withMockedBackend
     */
    public function test__close__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('close', $args, $return, $expect);
    }

    public static function prov__close__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('close', []);
    }

    /**
     * @dataProvider prov__close__withInvalidArgType
     */
    public function test__close__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('close', $resource, $args, $exception, $message);
    }

    public function test__close__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('close', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // compare()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__compare__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'value'],
                'return' => true,
                'expect' => true
            ],
            // #1
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'value', ['serverctls']],
                'return' => true,
                'expect' => true
            ],
            // #2
            [
                'args'   => ['', '', ''],
                'return' => false,
                'expect' => false
            ],
            // #3
            [
                'args'   => ['', '', ''],
                'return' => -1,
                'expect' => -1
            ],
            // #4
            [
                'args'   => ['', '', ''],
                'return' => null,
                'expect' => -1
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__compare__withMockedBackend
     */
    public function test__compare__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('compare', $args, $return, $expect);
    }

    public static function prov__compare__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('compare', ['string', 'string', 'string', 'array']);
    }

    /**
     * @dataProvider prov__compare__withInvalidArgType
     */
    public function test__compare__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('compare', $resource, $args, $exception, $message);
    }

    public function test__compare__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('compare', ['', '', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // connect()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__connect__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'ldap link 1',
                'expect' => ['getResource()' => 'ldap link 1'],
            ],
            // #1
            [
                'args'   => ['ldapi:///'],
                'return' => 'ldap link 2',
                'expect' => ['getResource()' => 'ldap link 2'],
            ],
            // #2
            [
                'args'   => ['localhost', 123],
                'return' => 'ldap link 3',
                'expect' => ['getResource()' => 'ldap link 3'],
            ],
            // #3
            [
                'args'   => [null],
                'return' => 'ldap link 4',
                'expect' => ['getResource()' => 'ldap link 4'],
            ],
            // #4
            [
                'args'   => [null, 123],
                'return' => 'ldap link 5',
                'expect' => ['getResource()' => 'ldap link 5'],
            ],
            // #5
            [
                'args'   => ['ldapi:///', 123],
                'return' => 'ldap link 6',
                'expect' => ['getResource()' => 'ldap link 6'],
            ],
            // #6
            [
                'args'   => ['#@#@'],
                'return' => false,
                'expect' => false,
            ],
            // #7
            [
                'args'   => ['#@#@'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__connect__withMockedBackend
     */
    public function test__connect__withMockedBackend(array $args, $return, $expect) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        if (count($args) === 2 && $args[1] === null) {
            unset($ldapArgs[1]);
        }
        $this   ->getLdapFunctionMock("ldap_connect")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $ldap = LdapLink::connect(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapLink::class, $ldap);
            $this->assertSame($return, $ldap->getResource());
            $this->assertHasPropertiesSameAs($expect, $ldap);
        } else {
            $this->assertSame($expect, $ldap);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // delete()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__delete__withMockedBackend() : array
    {
        return static::feedDeleteWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__delete__withMockedBackend
     */
    public function test__delete__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('delete', $args, $return, $expect);
    }

    public static function prov__delete__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('delete', ['string', 'array']);
    }

    /**
     * @dataProvider prov__delete__withInvalidArgType
     */
    public function test__delete__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('delete', $resource, $args, $exception, $message);
    }

    public function test__delete__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('delete', ['']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // dn2ufn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__dn2ufn__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org'],
                'return' => 'example.com',
                'expect' => 'example.com',
            ],
            // #1
            [
                'args'   => ['dc=acme,$#$#'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['$#$'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__dn2ufn__withMockedBackend
     */
    public function test__dn2ufn__withMockedBackend(array $args, $return, $expect) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);

        $this   ->getLdapFunctionMock("ldap_dn2ufn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::dn2ufn(...$args));
    }

    public static function prov__dn2ufn__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('dn2ufn', ['string'], false);
    }

    /**
     * @dataProvider prov__dn2ufn__withInvalidArgType
     */
    public function test__dn2ufn__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('dn2ufn', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // err2str()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__err2str__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [2],
                'return' => 'Protocol error',
                'expect' => 'Protocol error',
            ],
            // #1
            [
                'args'   => [-321],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [-10000000],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__err2str__withMockedBackend
     */
    public function test__err2str__withMockedBackend(array $args, $return, $expect) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);

        $this   ->getLdapFunctionMock("ldap_err2str")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::err2str(...$args));
    }

    public static function prov__err2str__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('err2str', ['int'], false);
    }

    /**
     * @dataProvider prov__err2str__withInvalidArgType
     */
    public function test__err2str__withInvalidArgType(
        $resource,
        array $args,
        string $exception,
        string $message
    ) : void {
        static::examineMethodWithInvalidArgType('err2str', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // errno()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__errno__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 123,
                'expect' => 123,
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
     * @dataProvider prov__errno__withMockedBackend
     */
    public function test__errno__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('errno', $args, $return, $expect);
    }

    public static function prov__errno__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('errno', []);
    }

    /**
     * @dataProvider prov__errno__withInvalidArgType
     */
    public function test__errno__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('errno', $resource, $args, $exception, $message);
    }

    public function test__errno__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('errno', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // error()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__error__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'Protocol error',
                'expect' => 'Protocol error',
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
     * @dataProvider prov__error__withMockedBackend
     */
    public function test__error__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('error', $args, $return, $expect);
    }

    public static function prov__error__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('error', []);
    }

    /**
     * @dataProvider prov__error__withInvalidArgType
     */
    public function test__error__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('error', $resource, $args, $exception, $message);
    }

    public function test__error__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('error', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // escape()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__escape__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org'],
                'return' => '\64\63\3d\65\78\61\6d\70\6c\65\2c\64\63\3d\6f\72\67',
                'expect' => '\64\63\3d\65\78\61\6d\70\6c\65\2c\64\63\3d\6f\72\67',
            ],
            // #1
            [
                'args'   => ['dc=example,dc=org', '=,'],
                'return' => '\64\63=\65\78\61\6d\70\6c\65,\64\63=\6f\72\67',
                'expect' => '\64\63=\65\78\61\6d\70\6c\65,\64\63=\6f\72\67',
            ],
            // #2
            [
                'args'   => ['dc=example,dc=org', '', LDAP_ESCAPE_DN],
                'return' => 'dc\3dexample\2cdc\3dorg',
                'expect' => 'dc\3dexample\2cdc\3dorg',
            ],
            // #3
            [
                'args'   => ['', '', -123],
                'return' => false,
                'expect' => false
            ],
            // #4
            [
                'args'   => ['', '', -456],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__escape__withMockedBackend
     */
    public function test__escape__withMockedBackend(array $args, $return, $expect) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        $this   ->getLdapFunctionMock("ldap_escape")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::escape(...$args));
    }

    public static function prov__escape__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('escape', ['string', 'string', 'int'], false);
    }

    /**
     * @dataProvider prov__escape__withInvalidArgType
     */
    public function test__escape__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('escape', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // explode_dn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__explode_dn__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 0],
                'return' => ['dc=example', 'dc=org', 'count' => 2],
                'expect' => ['dc=example', 'dc=org', 'count' => 2],
            ],
            // #1
            [
                'args'   => ['***', -1],
                'return' => false,
                'expect' => false
            ],
            // #2
            [
                'args'   => ['***', -2],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__explode_dn__withMockedBackend
     */
    public function test__explode_dn__withMockedBackend(array $args, $return, $expect) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        $this   ->getLdapFunctionMock("ldap_explode_dn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);
        $this->assertSame($expect, LdapLink::explode_dn(...$args));
    }

    public static function prov__explode_dn__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('explode_dn', ['string', 'int'], false);
    }

    /**
     * @dataProvider prov__explode_dn__withInvalidArgType
     */
    public function test__explode_dn__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('explode_dn', $resource, $args, $exception, $message);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_option()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_option__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [1, &$rv1],
                'return' => true,
                'expect' => true,
                'values' => [123],
            ],
            // #1
            [
                'args'   => [2, &$rv2],
                'return' => true,
                'expect' => true,
                'values' => ['foo'],
            ],
            // #2
            [
                'args'   => [2, &$rv3],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],
            // #3
            [
                'args'   => [-1, &$rv4],
                'return' => null,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_option__withMockedBackend
     */
    public function test__get_option__withMockedBackend(array $args, $return, $expect, array $values) : void
    {
        $ldap = new LdapLink('ldap link');
        $ldapArgs = $this->makeArgsForLdapFunctionMock([$ldap], $args);

        $this   ->getLdapFunctionMock("ldap_get_option")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(new LdapGetOptionClosure($return, $values));

        $this->assertSame($expect, $ldap->get_option(...$args));
        if (count($args) > 1) {
            $this->assertSame($values[0] ?? null, $args[1]);
        }
    }

    public static function prov__get_option__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('get_option', ['int'], null, [&$var]);
    }

    /**
     * @dataProvider prov__get_option__withInvalidArgType
     */
    public function test__get_option__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        static::examineMethodWithInvalidArgType('get_option', $resource, $args, $exception, $message);
    }

    public function test__get_option__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('get_option', [0, &$var]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // list()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__list__withMockedBackend() : array
    {
        return static::feedSearchWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__list__withMockedBackend
     */
    public function test__list__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('list', $args, $return, $expect);
    }

    public static function prov__list__withInvalidArgType() : array
    {
        $types  = ['string', 'string', 'array', 'int', 'int', 'int', 'int', 'array'];
        return static::feedFuncWithInvalidArgType('list', $types, false);
    }

    /**
     * @dataProvider prov__list__withInvalidArgType
     */
    public function test__list__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('list', $resource, $args, $exception, $message);
    }

    public function test__list__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('list', ['', '']);
    }

    public static function prov__mod_add__withMockedBackend() : array
    {
        return static::feedModifyWithMockedBackend();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // mod_add()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__mod_add__withMockedBackend
     */
    public function test__mod_add__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('mod_add', $args, $return, $expect);
    }

    public static function prov__mod_add__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('mod_add', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider prov__mod_add__withInvalidArgType
     */
    public function test__mod_add__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('mod_add', $resource, $args, $exception, $message);
    }

    public function test__mod_add__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('mod_add', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // mod_del()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__mod_del__withMockedBackend() : array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__mod_del__withMockedBackend
     */
    public function test__mod_del__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('mod_del', $args, $return, $expect);
    }

    public static function prov__mod_del__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('mod_del', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider prov__mod_del__withInvalidArgType
     */
    public function test__mod_del__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('mod_del', $resource, $args, $exception, $message);
    }

    public function test__mod_del__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('mod_del', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // mod_replace()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__mod_replace__withMockedBackend() : array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__mod_replace__withMockedBackend
     */
    public function test__mod_replace__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('mod_replace', $args, $return, $expect);
    }

    public static function prov__mod_replace__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('mod_replace', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider prov__mod_replace__withInvalidArgType
     */
    public function test__mod_replace__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('mod_replace', $resource, $args, $exception, $message);
    }

    public function test__mod_replace__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('mod_replace', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // modify_batch()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__modify_batch__withMockedBackend() : array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__modify_batch__withMockedBackend
     */
    public function test__modify_batch__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('modify_batch', $args, $return, $expect);
    }

    public static function prov__modify_batch__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('modify_batch', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider prov__modify_batch__withInvalidArgType
     */
    public function test__modify_batch__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('modify_batch', $resource, $args, $exception, $message);
    }

    public function test__modify_batch__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('modify_batch', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // modify()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__modify__withMockedBackend() : array
    {
        return static::feedModifyWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__modify__withMockedBackend
     */
    public function test__modify__withMockedBackend(array $args, $return, $expect) : void
    {
        static::examineLdapMethod('modify', $args, $return, $expect);
    }

    public static function prov__modify__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('modify', ['string', 'array', 'array']);
    }

    /**
     * @dataProvider prov__modify__withInvalidArgType
     */
    public function test__modify__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('modify', $resource, $args, $exception, $message);
    }

    public function test__modify__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('modify', ['', []]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // read()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__read__withMockedBackend() : array
    {
        return static::feedSearchWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__read__withMockedBackend
     */
    public function test__read__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('read', $args, $return, $expect);
    }

    public static function prov__read__withInvalidArgType() : array
    {
        $types  = ['string', 'string', 'array', 'int', 'int', 'int', 'int', 'array'];
        return static::feedFuncWithInvalidArgType('read', $types, false);
    }

    /**
     * @dataProvider prov__read__withInvalidArgType
     */
    public function test__read__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('read', $resource, $args, $exception, $message);
    }

    public function test__read__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('read', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // rename()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__rename__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['cn=foo,dc=example,dc=org', 'cn=bar', '', false],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => ['cn=foo,dc=example,dc=org', 'cn=bar', '', false, ['serverctls']],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['', '', '', false],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => ['', '', '', false],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__rename__withMockedBackend
     */
    public function test__rename__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('rename', $args, $return, $expect);
    }

    public static function prov__rename__withInvalidArgType() : array
    {
        $types  = ['string', 'string', 'string', 'bool', 'array'];
        return static::feedFuncWithInvalidArgType('rename', $types);
    }

    /**
     * @dataProvider prov__rename__withInvalidArgType
     */
    public function test__rename__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('rename', $resource, $args, $exception, $message);
    }

    public function test__rename__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('rename', ['', '', '', false]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // sasl_bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__sasl_bind__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => ['cn=admin,dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t'],
                'return' => true,
                'expect' => true,
            ],

            // #3
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech'],
                'return' => true,
                'expect' => true,
            ],

            // #4
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm'],
                'return' => true,
                'expect' => true,
            ],

            // #5
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id'],
                'return' => true,
                'expect' => true,
            ],

            // #6
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id'],
                'return' => true,
                'expect' => true,
            ],

            // #7
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id', 'props'],
                'return' => true,
                'expect' => true,
            ],

            // #8
            [
                'args'   => ['$$'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__sasl_bind__withMockedBackend
     */
    public function test__sasl_bind__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('sasl_bind', $args, $return, $expect);
    }

    public static function prov__sasl_bind__withInvalidArgType() : array
    {
        $types  = ['string', 'string', 'string', 'string', 'string', 'string', 'string'];
        return static::feedFuncWithInvalidArgType('sasl_bind', $types);
    }

    /**
     * @dataProvider prov__sasl_bind__withInvalidArgType
     */
    public function test__sasl_bind__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('sasl_bind', $resource, $args, $exception, $message);
    }

    public function test__sasl_bind__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('sasl_bind', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // search()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__search__withMockedBackend() : array
    {
        return static::feedSearchWithMockedBackend();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__search__withMockedBackend
     */
    public function test__search__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('search', $args, $return, $expect);
    }

    public static function prov__search__withInvalidArgType() : array
    {
        $types  = ['string', 'string', 'array', 'int', 'int', 'int', 'int', 'array'];
        return static::feedFuncWithInvalidArgType('search', $types, false);
    }

    /**
     * @dataProvider prov__search__withInvalidArgType
     */
    public function test__search__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('search', $resource, $args, $exception, $message);
    }

    public function test__search__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('search', ['', '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // set_option()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__set_option__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [1, 'a'],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => [2, ['x']],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args'   => [0, ''],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => [-1, null],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__set_option__withMockedBackend
     */
    public function test__set_option__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('set_option', $args, $return, $expect);
    }

    public static function prov__set_option__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('set_option', ['int'], false, ['x']);
    }

    /**
     * @dataProvider prov__set_option__withInvalidArgType
     */
    public function test__set_option__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('set_option', $resource, $args, $exception, $message);
    }

    public function test__set_option__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('set_option', [0, '']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // set_rebind_proc()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__set_rebind_proc__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => ['foo'],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => [''], // unsetting
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['inexistent'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__set_rebind_proc__withMockedBackend
     */
    public function test__set_rebind_proc__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('set_rebind_proc', $args, $return, $expect);
    }

    public static function prov__set_rebind_proc__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('set_rebind_proc', [], null, [function () {
        }]);
    }

    /**
     * @dataProvider prov__set_rebind_proc__withInvalidArgType
     */
    public function test__set_rebind_proc__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('set_rebind_proc', $resource, $args, $exception, $message);
    }

    public function test__set_rebind_proc__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('set_rebind_proc', ['']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // start_tls()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__start_tls__withMockedBackend() : array
    {
        return [
            // #1
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__start_tls__withMockedBackend
     */
    public function test__start_tls__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('start_tls', $args, $return, $expect);
    }

    public static function prov__unbind__withMockedBackend() : array
    {
        return [
            // #1
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    public static function prov__start_tls__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('start_tls', []);
    }

    /**
     * @dataProvider prov__start_tls__withInvalidArgType
     */
    public function test__start_tls__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('start_tls', $resource, $args, $exception, $message);
    }

    public function test__start_tls__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('start_tls', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unbind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__unbind__withMockedBackend
     */
    public function test__unbind__withMockedBackend(array $args, $return, $expect) : void
    {
        $this->examineLdapMethod('unbind', $args, $return, $expect);
    }

    public static function prov__unbind__withInvalidArgType() : array
    {
        return static::feedFuncWithInvalidArgType('unbind', []);
    }

    /**
     * @dataProvider prov__unbind__withInvalidArgType
     */
    public function test__unbind__withInvalidArgType($resource, array $args, string $exception, string $message) : void
    {
        $this->examineMethodWithInvalidArgType('unbind', $resource, $args, $exception, $message);
    }

    public function test__unbind__withInvalidLdapLink()
    {
        $this->examineMethodWithInvalidLdapLink('unbind', []);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unset()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function test__unset__doesNotFreeTheResource() : void
    {
        $ldap = new LdapLink('ldap link');

        $this->getLdapFunctionMock('ldap_unbind')
             ->expects($this->never());

        $this->getLdapFunctionMock('ldap_close')
             ->expects($this->never());

        unset($ldap);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
