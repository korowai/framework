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
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\CompareQuery;
use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\CompareQuery
 */
final class CompareQueryTest extends TestCase
{
//    use CreateLdapLinkMockTrait;
    use ExamineCallWithLdapTriggerErrorTrait;

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

    public function prov__query__withLdapReturningFailure() : array
    {
        $common =  [
            'args'   => ['dc=example,dc=org', 'attribute', 'value'],
            'return' => -1,
            'expect' => [
                'exception' => \ErrorException::class,
                'message'   => 'LdapLink::compare() returned -1'
            ],
        ];
        return [
            // # 0
            ['method' => 'execute'] +  $common,
            // # 1
            ['method' => 'getResult'] + $common,
        ];
    }

    public static function prov__query__withLdapTriggerError() : array
    {
        $common = self::feedCallWithLdapTriggerError();
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
    public function test__query__withLdapTriggerError(string $method, array $config, array $expect): void
    {
        $args = ['dc=example,dc=org', 'attribute', 'value'];
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $query = new CompareQuery($link, ...$args);
        $function = [$query, $method];

        $this->examineCallWithLdapTriggerError($function, $link, 'compare', $args, $link, $config, $expect);
    }

    /**
     * @dataProvider prov__query__withLdapReturningFailure
     */
    public function test__query__withLdapReturningFailure(string $method, array $args, $return, array $expect) : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $query = new CompareQuery($link, ...$args);

        $link->expects($this->once())
             ->method('compare')
             ->with(...$args)
             ->willReturn($return);

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);

        $query->execute();
    }
}

// vim: syntax=php sw=4 ts=4 et:
