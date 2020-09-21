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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;

use Korowai\Lib\Ldap\CompareQuery;
use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Exception\ErrorException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\CompareQuery
 */
final class CompareQueryTest extends TestCase
{
//    use CreateLdapLinkMockTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    //
    //
    // TESTS
    //
    //

    public function test__implements__CompareQueryInterface() : void
    {
        $this->assertImplementsInterface(CompareQueryInterface::class, CompareQuery::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, CompareQuery::class);
    }

    //
    // __construct()
    //

    public function test__construct() : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $query = new CompareQuery($link, 'dc=example,dc=org', 'attribute', 'value');
        $this->assertSame($link, $query->getLdapLink());
        $this->assertHasPropertiesSameAs([
            'getDn()' => "dc=example,dc=org",
            'getAttribute()' => 'attribute',
            'getValue()' => 'value'
        ], $query);
    }

    //
    // execute()/getResult()
    //

    public static function prov__query() : array
    {
        $common = [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'matching'],
                'return' => false,
                'expect' => false,
            ],
            // #1
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'non-matching'],
                'return' => true,
                'expect' => true,
            ],
        ];

        return [
            [ 'method' => 'execute',   'calls' => 2] +  $common[0],
            [ 'method' => 'execute',   'calls' => 2] +  $common[1],
            [ 'method' => 'getResult', 'calls' => 1] +  $common[0],
            [ 'method' => 'getResult', 'calls' => 1] +  $common[1],
        ];
    }

    /**
     * @dataProvider prov__query
     */
    public function test__query(string $method, int $calls, array $args, $return, $expect) : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $query = new CompareQuery($link, ...$args);
        $link->expects($this->exactly($calls))
             ->method('compare')
             ->with(...$args)
             ->willReturn($return);
        $this->assertSame($expect, $query->$method());
        $this->assertSame($expect, $query->$method());
    }

    public static function prov__query__withLdapTriggerError() : array
    {
        $common = self::feedLdapLinkErrorHandler();
        foreach (['execute', 'getResult'] as $method) {
            foreach ($common as $key => $array) {
                $cases[] = ['method' => $method] + $array;
            }
        }
        return $cases;
    }

    /**
     * @dataProvider prov__query__withLdapTriggerError
     */
    public function test__query__withLdapTriggerError(string $method, LdapTriggerErrorTestFixture $fixture): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $query = new CompareQuery($link, '', '', '');
        $function = [$query, $method];

        $subject = new LdapTriggerErrorTestSubject($link, 'compare');
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public function prov__query__withLdapReturningFailure() : array
    {
        return [
            // # 0
            ['method' => 'execute'],
            // # 1
            ['method' => 'getResult'],
        ];
    }

    /**
     * @dataProvider prov__query__withLdapReturningFailure
     */
    public function test__query__withLdapReturningFailure(string $method) : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $query = new CompareQuery($link, '', '', '');

        $link->expects($this->once())
             ->method('compare')
             ->willReturn(-1);

        $link->expects($this->once())
             ->method('getErrorHandler')
             ->willReturn(new LdapLinkErrorHandler($link));

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::compare() returned -1');

        $query->execute();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: