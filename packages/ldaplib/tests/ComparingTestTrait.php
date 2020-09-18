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

use PHPUnit\Framework\MockObject\MockObject;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\CompareQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Exception\ErrorException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ComparingTestTrait
{
    abstract public function createComparingInstance(LdapLinkInterface $ldapLink) : ComparingInterface;

    abstract public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ) : void;

    abstract public static function feedLdapLinkErrorHandler() : array;

    abstract protected function createMock(string $class);

    //
    //
    // TESTS
    //
    //

    //
    // createCompareQuery()
    //
    public function test__createCompareQuery() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $comparator = $this->createComparingInstance($link);

        $query = $comparator->createCompareQuery('dc=example,dc=org', 'foo', 'bar');

        $this->assertInstanceOf(CompareQuery::class, $query);
        $this->assertHasPropertiesSameAs([
            'getLdapLink()' => $link,
            'getDn()' => 'dc=example,dc=org',
            'getAttribute()' => 'foo',
            'getValue()' => 'bar'
        ], $query);
    }

    //
    // compare()
    //

    public static function prov__compare() : array
    {
        return [
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
    }

    /**
     * @dataProvider prov__compare
     */
    public function test__compare(array $args, $return, $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $comparator = $this->createComparingInstance($link);
        $link->expects($this->exactly(2))
             ->method('compare')
             ->with(...$args)
             ->willReturn($return);
        $this->assertSame($expect, $comparator->compare(...$args));
        $this->assertSame($expect, $comparator->compare(...$args));
    }

    public static function prov__compare__withLdapTriggerError() : array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__compare__withLdapTriggerError
     */
    public function test__compare__withLdapTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $comparator = $this->createComparingInstance($link);
        $function = function () use ($comparator) {
            return $comparator->compare('', '', '');
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'compare');

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public function test__compare__withLdapReturningFailure() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $comparator = $this->createComparingInstance($link);

        $link->expects($this->once())
             ->method('getErrorHandler')
             ->willReturn(new LdapLinkErrorHandler($link));

        $link->expects($this->once())
             ->method('compare')
             ->willReturn(-1);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::compare() returned -1');

        $comparator->compare('', '', '');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
