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

use Korowai\Lib\Ldap\CompareQuery;
use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\CompareQuery
 *
 * @internal
 */
final class CompareQueryTest extends TestCase
{
//    use CreateLdapLinkMockTrait;
    use ExamineLdapLinkErrorHandlerTrait;
    use ImplementsInterfaceTrait;
    use ObjectPropertiesIdenticalToTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsCompareQueryInterface(): void
    {
        $this->assertImplementsInterface(CompareQueryInterface::class, CompareQuery::class);
    }

    public function testImplementsLdapLinkWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, CompareQuery::class);
    }

    //
    // __construct()
    //

    public function testConstruct(): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $query = new CompareQuery($link, 'dc=example,dc=org', 'attribute', 'value');
        $this->assertSame($link, $query->getLdapLink());
        $this->assertObjectPropertiesIdenticalTo([
            'getDn()' => 'dc=example,dc=org',
            'getAttribute()' => 'attribute',
            'getValue()' => 'value',
        ], $query);
    }

    //
    // execute()/getResult()
    //

    public static function provQuery(): array
    {
        $common = [
            // #0
            [
                'args' => ['dc=example,dc=org', 'attribute', 'matching'],
                'return' => false,
                'expect' => false,
            ],
            // #1
            [
                'args' => ['dc=example,dc=org', 'attribute', 'non-matching'],
                'return' => true,
                'expect' => true,
            ],
        ];

        return [
            ['method' => 'execute',   'calls' => 2] + $common[0],
            ['method' => 'execute',   'calls' => 2] + $common[1],
            ['method' => 'getResult', 'calls' => 1] + $common[0],
            ['method' => 'getResult', 'calls' => 1] + $common[1],
        ];
    }

    /**
     * @dataProvider provQuery
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testQuery(string $method, int $calls, array $args, $return, $expect): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $query = new CompareQuery($link, ...$args);
        $link->expects($this->exactly($calls))
            ->method('compare')
            ->with(...$args)
            ->willReturn($return)
        ;
        $this->assertSame($expect, $query->{$method}());
        $this->assertSame($expect, $query->{$method}());
    }

    public static function provQueryWithLdapTriggerError(): array
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
     * @dataProvider provQueryWithLdapTriggerError
     */
    public function testQueryWithLdapTriggerError(string $method, LdapTriggerErrorTestFixture $fixture): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $query = new CompareQuery($link, '', '', '');
        $function = [$query, $method];

        $subject = new LdapTriggerErrorTestSubject($link, 'compare');
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public function provQueryWithLdapReturningFailure(): array
    {
        return [
            // # 0
            ['method' => 'execute'],
            // # 1
            ['method' => 'getResult'],
        ];
    }

    /**
     * @dataProvider provQueryWithLdapReturningFailure
     */
    public function testQueryWithLdapReturningFailure(string $method): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $query = new CompareQuery($link, '', '', '');

        $link->expects($this->once())
            ->method('compare')
            ->willReturn(-1)
        ;

        $link->expects($this->once())
            ->method('getErrorHandler')
            ->willReturn(new LdapLinkErrorHandler($link))
        ;

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::compare() returned -1');

        $query->execute();
    }
}

// vim: syntax=php sw=4 ts=4 et:
