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
use Korowai\Lib\Ldap\Adapter\AbstractSearchQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\SearchQuery;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\ResultInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class SearchQueryTest extends TestCase
{
//    use \phpmock\phpunit\PHPMock;
//    use GetLdapFunctionMockTrait;

    use CreateLdapLinkMockTrait;
    use ExamineMethodWithBackendTriggerErrorTrait;

    private function examineMethodWithTriggerError(
        string $method,
        array $args,
        string $ldapMethod,
        array $ldapArgs,
        array $config,
        array $expect
    ) : void {
        $ldap = $this->createLdapLinkMock('ldap link', ['isValid', 'errno']);
        $query = new SearchQuery($ldap, ...$args);

        $this->examineMethodWithBackendTriggerError(
            $query,
            $method,
            $args,
            $ldap,
            $ldapMethod,
            $ldapArgs,
            $ldap,
            $config,
            $expect
        );
    }

    //
    //
    // TESTS
    //
    //

    public function test__extends__AbstractSearchQuery() : void
    {
        $this->assertExtendsClass(AbstractSearchQuery::class, SearchQuery::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, SearchQuery::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, SearchQuery::class);
    }

    public function test__construct() : void
    {
        $link = $this->createLdapLinkMock();
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*");
        $this->assertSame($link, $query->getLdapLink());
        $this->assertSame('dc=korowai,dc=org', $query->getBaseDn());
        $this->assertSame('objectClass=*', $query->getFilter());

        $this->markTestIncomplete('Need to test getOptions() as well');
    }

    public function test__construct__withInvalidOptions() : void
    {
        $link = $this->createLdapLinkMock();

        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessageMatches('/option "scope" with value "foo"/');

        new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'foo']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // execute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__execute() : array
    {
        return [
            // #0
            '$options = ["scope" => "base"]' => [
                'args' => [
                    'dc=korowai,dc=org',
                    'objectClass=*',
                    ['scope' => 'base']
                ],
                'expect' => [
                    'method' => 'read',
                    'args' => [
                        'dc=korowai,dc=org',
                        'objectClass=*',
                        ['*'],
                        0, 0, 0,
                        LDAP_DEREF_NEVER
                    ]
                ]
            ],
            // #1
            '$options = ["scope" => "one"]' => [
                'args' => [
                    'dc=korowai,dc=org',
                    'objectClass=*',
                    ['scope' => 'one']
                ],
                'expect' => [
                    'method' => 'list',
                    'args' => [
                        'dc=korowai,dc=org',
                        'objectClass=*',
                        ['*'],
                        0, 0, 0,
                        LDAP_DEREF_NEVER
                    ]
                ]
            ],
            // #2
            '$options = ["scope" => "sub"]' => [
                'args' => [
                    'dc=korowai,dc=org',
                    'objectClass=*',
                    ['scope' => 'sub']
                ],
                'expect' => [
                    'method' => 'search',
                    'args' => [
                        'dc=korowai,dc=org',
                        'objectClass=*',
                        ['*'],
                        0, 0, 0,
                        LDAP_DEREF_NEVER
                    ]
                ]
            ],
            // #2
            "default options" => [
                'args' => [
                    'dc=korowai,dc=org',
                    'objectClass=*',
                ],
                'expect' => [
                    'method' => 'search',
                    'args' => [
                        'dc=korowai,dc=org',
                        'objectClass=*',
                        ['*'],
                        0, 0, 0,
                        LDAP_DEREF_NEVER
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider prov__execute
     */
    public function test__execute(array $args, array $expect) : void
    {
        $link = $this->createLdapLinkMock();
        $ldapResult = $this->createMock(LdapResultInterface::class);
        $query = new SearchQuery($link, ...$args);
        $link->expects($this->once())
             ->method($expect['method'])
             ->with(...$expect['args'])
             ->willReturn($ldapResult);
        $result = $query->execute();
        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertSame($ldapResult, $result->getLdapResult());
        $this->assertSame($result, $query->getResult());
    }

    public static function prov__execute__withTriggerError() : array
    {
        return static::feedMethodWithBackendTriggerError();
    }

    /**
     * @dataProvider prov__execute__withTriggerError
     */
    public function test__execute__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError(
            'execute',
            ['dc=example,dc=org', 'objectClass=*'],
            'search',
            ['dc=example,dc=org', 'objectClass=*', ['*'], 0, 0, 0, LDAP_DEREF_NEVER],
            $config,
            $expect
        );
    }

    public function test__execute__sub__withoutDeref()
    {
        $this->markTestIncomplete('Test not implemented yet!');
//        $link = $this->createLdapLinkMock(true);
//        $result = $this->createMock(ResultInterface::class);
//
//        $query = $this->getMockBuilder(SearchQuery::class)
//                      ->disableOriginalConstructor()
//                      ->setMethods(['getOptions', 'getLdapLink', 'getBaseDn', 'getFilter'])
//                      ->getMock();
//
//        $query->expects($this->any())
//               ->method('getBaseDn')
//               ->with()
//               ->willReturn("dc=korowai,dc=org");
//        $query->expects($this->any())
//               ->method('getFilter')
//               ->with()
//               ->willReturn("objectClass=*");
//        $query->expects($this->any())
//               ->method('getOptions')
//               ->with()
//               ->willReturn(['attributes' => ['*'], 'attrsOnly' => 0, 'sizeLimit' => 0, 'timeLimit' => 0]);
//        $query->expects($this->any())
//               ->method('getLdapLink')
//               ->with()
//               ->willReturn($link);
//
//        $link->expects($this->never())
//             ->method('read');
//        $link->expects($this->never())
//             ->method('list');
//        $link->expects($this->exactly(2))
//             ->method('search')
//             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
//             ->willReturn($result);
//
//        $this->assertSame($result, $query->execute());
//        $this->assertSame($result, $query->execute());
//        $this->assertSame($result, $query->getResult());
    }

    public function test__execute__withInvalidScope()
    {
        $this->markTestIncomplete('Test not implemented yet!');
//        $link = $this->createLdapLinkMock();
//
//        $query = $this->getMockBuilder(SearchQuery::class)
//                      ->disableOriginalConstructor()
//                      ->setMethods(['getOptions'])
//                      ->getMock();
//
//        $query->expects($this->once())
//               ->method('getOptions')
//               ->with()
//               ->willReturn(['scope' => 'foo']);
//
//        $this->expectException(\RuntimeException::class);
//        $this->expectExceptionMessage('Unsupported search scope "foo"');
//
//        $query->execute();
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getResult() : array
    {
        return static::prov__execute();
    }

    /**
     * @dataProvider prov__getResult
     */
    public function test__getResult(array $args, array $expect) : void
    {
        $link = $this->createLdapLinkMock();
        $ldapResult = $this->createMock(LdapResultInterface::class);
        $query = new SearchQuery($link, ...$args);
        $link->expects($this->once())
             ->method($expect['method'])
             ->with(...$expect['args'])
             ->willReturn($ldapResult);
        $result = $query->getResult();
        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertSame($ldapResult, $result->getLdapResult());
        $this->assertSame($result, $query->getResult());
    }

    public static function prov__getResult__withTriggerError() : array
    {
        return static::feedMethodWithBackendTriggerError();
    }

    /**
     * @dataProvider prov__getResult__withTriggerError
     */
    public function test__getResult__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError(
            'getResult',
            ['dc=example,dc=org', 'objectClass=*'],
            'search',
            ['dc=example,dc=org', 'objectClass=*', ['*'], 0, 0, 0, LDAP_DEREF_NEVER],
            $config,
            $expect
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
