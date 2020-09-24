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

use Korowai\Lib\Ldap\Core\LdapLinkConfigInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactory;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkFactory
 *
 * @internal
 */
final class LdapLinkFactoryTest extends TestCase
{
    use ExamineLdapLinkErrorHandlerTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapLinkFactoryInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkFactoryInterface::class, LdapLinkFactory::class);
    }

    //
    // __construct()
    //

    public function testConstruct(): void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $factory = new LdapLinkFactory($constructor);

        $this->assertSame($constructor, $factory->getLdapLinkConstructor());
    }

    //
    // createLdapLink()
    //

    public static function provCreateLdapLink(): array
    {
        return [
            // #0
            [
                'params' => [
                    'uri' => 'ldap:///',
                    'tls' => false,
                    'options' => [],
                ],
            ],

            // #1
            [
                'params' => [
                    'uri' => 'ldap:///',
                    'tls' => true,
                    'options' => [17 => 3],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provCreateLdapLink
     */
    public function testCreateLdapLink(array $params): void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link = $this->createMock(LdapLinkInterface::class);
        $config = $this->createMock(LdapLinkConfigInterface::class);

        $constructor->expects($this->once())
            ->method('connect')
            ->with($params['uri'])
            ->willReturn($link)
        ;

        foreach (['uri', 'tls', 'options'] as $key) {
            $config->expects($this->once())
                ->method($key)
                ->willReturn($params[$key])
            ;
        }

        if ($params['tls']) {
            $link->expects($this->once())
                ->method('start_tls')
            ;
        } else {
            $link->expects($this->never())
                ->method('start_tls')
            ;
        }

        if (!empty($options = $params['options'])) {
            $count = count($options);
            // convert [ 1 => 'ONE', 2 => 'TWO' ] to [[1, 'ONE'], [2, 'TWO']]
            $options = array_map(null, array_keys($options), $options);
            $link->expects($this->exactly($count))
                ->method('set_option')
                ->withConsecutive(...$options)
            ;
        } else {
            $link->expects($this->never())
                ->method('set_option')
            ;
        }

        $factory = new LdapLinkFactory($constructor);

        $this->assertSame($link, $factory->createLdapLink($config));
    }

    public static function provCreateLdapLinkWhenStartTlsTriggersError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provCreateLdapLinkWhenStartTlsTriggersError
     */
    public function testCreateLdapLinkWhenStartTlsTriggersError(LdapTriggerErrorTestFixture $fixture): void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link = $this->createMock(LdapLinkInterface::class);
        $config = $this->createMock(LdapLinkConfigInterface::class);

        $constructor->expects($this->once())
            ->method('connect')
            ->with('ldap:///')
            ->willReturn($link)
        ;

        $config->expects($this->once())
            ->method('uri')
            ->willReturn('ldap:///')
        ;

        $config->expects($this->once())
            ->method('tls')
            ->willReturn(true)
        ;

        $config->expects($this->any())
            ->method('options')
            ->willReturn([])
        ;

        $link->expects($this->never())
            ->method('set_option')
        ;

        $factory = new LdapLinkFactory($constructor);
        $function = function () use ($factory, $config): LdapLinkInterface {
            return $factory->createLdapLink($config);
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'start_tls');

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public static function provCreateLdapLinkWhenSetOptionTriggersError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provCreateLdapLinkWhenSetOptionTriggersError
     */
    public function testCreateLdapLinkWhenSetOptionTriggersError(LdapTriggerErrorTestFixture $fixture): void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link = $this->createMock(LdapLinkInterface::class);
        $config = $this->createMock(LdapLinkConfigInterface::class);

        $constructor->expects($this->once())
            ->method('connect')
            ->with('ldap:///')
            ->willReturn($link)
        ;

        $config->expects($this->once())
            ->method('uri')
            ->willReturn('ldap:///')
        ;

        $config->expects($this->once())
            ->method('tls')
            ->willReturn(false)
        ;

        $config->expects($this->any())
            ->method('options')
            ->willReturn([17 => 3])
        ;

        $link->expects($this->never())
            ->method('start_tls')
        ;

        $factory = new LdapLinkFactory($constructor);

        $function = function () use ($factory, $config): LdapLinkInterface {
            return $factory->createLdapLink($config);
        };

        $subject = new LdapTriggerErrorTestSubject($link, 'set_option');

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
