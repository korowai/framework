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
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactory;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactory
 */
final class LdapLinkFactoryTest extends TestCase
{
    use ExamineCallWithLdapTriggerErrorTrait;

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

    public static function prov__construct() : array
    {
        return [
            // #0
            [
                'args' => ['ldap://example.org'],
                'expect' => [
                    'properties' => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => false,
                        'getOptions()'              => [],
                    ],
                ],
            ],

            // #1
            [
                'args' => ['ldap://example.org', false],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => false,
                        'getOptions()'              => [],
                    ],
                ],
            ],

            // #2
            [
                'args' => ['ldap://example.org', true],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => true,
                        'getOptions()'              => [],
                    ],
                ],
            ],

            // #3
            [
                'args' => ['ldap://example.org', true, ['foo' => 'bar']],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => true,
                        'getOptions()'              => ['foo' => 'bar'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $factory = new LdapLinkFactory($constructor, ...$args);

        $this->assertSame($constructor, $factory->getLdapLinkConstructor());
        $this->assertHasPropertiesSameAs($expect['properties'], $factory);
    }

    //
    // createLdapLink()
    //

    public static function prov__createLdapLink() : array
    {
        return [
            // #0
            [
                'args' => ['ldap://example.org'],
                'expect' => [
                    'properties' => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => false,
                        'getOptions()'              => [],
                    ],
                ],
            ],

            // #1
            [
                'args' => ['ldap://example.org', false],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => false,
                        'getOptions()'              => [],
                    ],
                ],
            ],

            // #2
            [
                'args' => ['ldap://example.org', true],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => true,
                        'getOptions()'              => [],
                    ],
                ],
            ],

            // #3
            [
                'args' => ['ldap://example.org', true, [123 => 'foo']],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => true,
                        'getOptions()'              => ['foo' => 'bar'],
                    ],
                ],
            ],

            // #4
            [
                'args' => ['ldap://example.org', true, [2 => 'two', 1 => 'one']],
                'expect' => [
                    'properties'  => [
                        'getUri()'                  => 'ldap://example.org',
                        'getTls()'                  => true,
                        'getOptions()'              => ['foo' => 'bar'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__createLdapLink
     */
    public function test__createLdapLink(array $args, array $expect) : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link = $this       ->createMock(LdapLinkInterface::class);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with($args[0])
                    ->willReturn($link);

        if (($tls = ($args[1] ?? false)) === true) {
            $link->expects($this->once())
                 ->method('start_tls')
                 ->with();
        } else {
            $link->expects($this->never())
                 ->method('start_tls');
        }

        if (($options = ($args[2] ?? null)) !== null && !empty($options)) {
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

        $factory = new LdapLinkFactory($constructor, ...$args);

        $this->assertSame($link, $factory->createLdapLink());
    }

    public static function prov__createLdapLink__whenStartTlsTriggersError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__createLdapLink__whenStartTlsTriggersError
     */
    public function test__createLdapLink__whenStartTlsTriggersError(array $config, array $expect) : void
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $link        = $this->createMock(LdapLinkInterface::class);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with('ldap:///')
                    ->willReturn($link);

        $link->expects($this->never())
             ->method('set_option');

        $factory = new LdapLinkFactory($constructor, 'ldap:///', true);

        $this->examineCallWithLdapTriggerError(
            [$factory, 'createLdapLink'],
            $link, 'start_tls', [],
            $link,
            $config,
            $expect
        );
    }

    public static function prov__createLdapLink__whenSetOptionTriggersError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__createLdapLink__whenSetOptionTriggersError
     */
    public function test__createLdapLink__whenSetOptionTriggersError(array $config, array $expect) : void
    {
        $constructor = $this->getMockBuilder(LdapLinkConstructorInterface::class)
                            ->setMethods(['connect'])
                            ->getMockForAbstractClass();
        $link        = $this->createMock(LdapLinkInterface::class);

        $link->expects($this->never())
             ->method('start_tls');

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with('ldap:///')
                    ->willReturn($link);

        $factory = new LdapLinkFactory($constructor, 'ldap:///', false, [17 => 3]);

        $this->examineCallWithLdapTriggerError(
            [$factory, 'createLdapLink'],
            $link, 'set_option', [17, 3],
            $link,
            $config,
            $expect
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
