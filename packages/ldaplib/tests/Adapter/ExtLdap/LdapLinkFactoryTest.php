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
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactory;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactory
 */
final class LdapLinkFactoryTest extends TestCase
{
    use ExamineLdapLinkErrorHandlerTrait;

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkFactoryInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkFactoryInterface::class, LdapLinkFactory::class);
    }

    //
    // __construct()
    //

    public function test__construct() : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $factory = new LdapLinkFactory($constructor);

        $this->assertSame($constructor, $factory->getLdapLinkConstructor());
    }

    //
    // createLdapLink()
    //

    public static function prov__createLdapLink() : array
    {
        return [
            // #0
            [
                'params' => [
                    'uri'     => 'ldap:///',
                    'tls'     => false,
                    'options' => []
                ],
            ],

            // #1
            [
                'params' => [
                    'uri'     => 'ldap:///',
                    'tls'     => true,
                    'options' => [17 => 3],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__createLdapLink
     */
    public function test__createLdapLink(array $params) : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link        = $this->createMock(LdapLinkInterface::class);
        $config      = $this->createMock(LdapLinkConfigInterface::class);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with($params['uri'])
                    ->willReturn($link);

        foreach (['uri', 'tls', 'options'] as $key) {
            $config->expects($this->once())
                   ->method($key)
                   ->with()
                   ->willReturn($params[$key]);
        }

        if ($params['tls']) {
            $link->expects($this->once())
                 ->method('start_tls')
                 ->with();
        } else {
            $link->expects($this->never())
                 ->method('start_tls');
        }

        if (!empty($options = $params['options'])) {
            $count = count($options);
            // convert [ 1 => 'ONE', 2 => 'TWO' ] to [[1, 'ONE'], [2, 'TWO']]
            $options = array_map(null, array_keys($options), $options);
            $link->expects($this->exactly($count))
                 ->method('set_option')
                 ->withConsecutive(...$options);
        } else {
            $link->expects($this->never())
                 ->method('set_option');
        }

        $factory = new LdapLinkFactory($constructor);

        $this->assertSame($link, $factory->createLdapLink($config));
    }

    public static function prov__createLdapLink__whenStartTlsTriggersError() : array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__createLdapLink__whenStartTlsTriggersError
     */
    public function test__createLdapLink__whenStartTlsTriggersError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link        = $this->createMock(LdapLinkInterface::class);
        $config      = $this->createMock(LdapLinkConfigInterface::class);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with('ldap:///')
                    ->willReturn($link);

        $config->expects($this->once())
               ->method('uri')
               ->with()
               ->willReturn('ldap:///');

        $config->expects($this->once())
               ->method('tls')
               ->with()
               ->willReturn(true);

        $config->expects($this->any())
               ->method('options')
               ->with()
               ->willReturn([]);

        $link->expects($this->never())
             ->method('set_option');

        $factory = new LdapLinkFactory($constructor);
        $function = function () use ($factory, $config) : LdapLinkInterface {
            return $factory->createLdapLink($config);
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'start_tls', []);

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public static function prov__createLdapLink__whenSetOptionTriggersError() : array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__createLdapLink__whenSetOptionTriggersError
     */
    public function test__createLdapLink__whenSetOptionTriggersError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link        = $this->createMock(LdapLinkInterface::class);
        $config      = $this->createMock(LdapLinkConfigInterface::class);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with('ldap:///')
                    ->willReturn($link);

        $config->expects($this->once())
               ->method('uri')
               ->with()
               ->willReturn('ldap:///');

        $config->expects($this->once())
               ->method('tls')
               ->with()
               ->willReturn(false);

        $config->expects($this->any())
               ->method('options')
               ->with()
               ->willReturn([17 => 3]);

        $link->expects($this->never())
             ->method('start_tls');

        $factory = new LdapLinkFactory($constructor);

        $function = function () use ($factory, $config) : LdapLinkInterface {
            return $factory->createLdapLink($config);
        };

        $subject = new LdapTriggerErrorTestSubject($link, 'set_option', [17, 3]);

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
