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
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ComparingTestTrait
{
    abstract public function createComparingInstance(LdapLinkInterface $ldapLink): ComparingInterface;

    abstract public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ): void;

    abstract public static function feedLdapLinkErrorHandler(): array;

    //
    //
    // TESTS
    //
    //

    //
    // createCompareQuery()
    //
    public function testCreateCompareQuery(): void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $comparator = $this->createComparingInstance($link);

        $query = $comparator->createCompareQuery('dc=example,dc=org', 'foo', 'bar');

        $this->assertInstanceOf(CompareQuery::class, $query);
        $this->assertObjectPropertiesIdenticalTo([
            'getLdapLink()' => $link,
            'getDn()' => 'dc=example,dc=org',
            'getAttribute()' => 'foo',
            'getValue()' => 'bar',
        ], $query);
    }

    //
    // compare()
    //

    public static function provCompare(): array
    {
        return [
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
    }

    /**
     * @dataProvider provCompare
     *
     * @param mixed $return
     * @param mixed $expect
     */
    public function testCompare(array $args, $return, $expect): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $comparator = $this->createComparingInstance($link);
        $link->expects($this->exactly(2))
            ->method('compare')
            ->with(...$args)
            ->willReturn($return)
        ;
        $this->assertSame($expect, $comparator->compare(...$args));
        $this->assertSame($expect, $comparator->compare(...$args));
    }

    public static function provCompareWithLdapTriggerError(): array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provCompareWithLdapTriggerError
     */
    public function testCompareWithLdapTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $comparator = $this->createComparingInstance($link);
        $function = function () use ($comparator) {
            return $comparator->compare('', '', '');
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'compare');

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public function testCompareWithLdapReturningFailure(): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $comparator = $this->createComparingInstance($link);

        $link->expects($this->once())
            ->method('getErrorHandler')
            ->willReturn(new LdapLinkErrorHandler($link))
        ;

        $link->expects($this->once())
            ->method('compare')
            ->willReturn(-1)
        ;

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::compare() returned -1');

        $comparator->compare('', '', '');
    }

    abstract protected function createMock(string $class);
}

// vim: syntax=php sw=4 ts=4 et tw=119:
