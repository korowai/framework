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
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
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
    use CreateLdapLinkMockTrait;
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

    public function prov__construct() : array
    {
        $constructor = $this->createMock(LdapLinkConstructorInterface::class);
        $resolvers = [
            $this->createMock(LdapLinkConfigResolverInterface::class),
            $this->createMock(LdapLinkConfigResolverInterface::class),
            $this->createMock(LdapLinkConfigResolverInterface::class)
        ];

        $resolvers[0]->expects($this->once())
                     ->method('resolve')
                     ->with([])
                     ->willReturn(['x' => 'X']);

        $resolvers[1]->expects($this->once())
                     ->method('resolve')
                     ->with([])
                     ->willReturn(['y' => 'Y']);

        $resolvers[2]->expects($this->once())
                     ->method('resolve')
                     ->with(['a' => 'A'])
                     ->willReturn(['a' => 'B']);
        return [
            // #0
            [
                'args' => [$constructor, $resolvers[0]],
                'expect' => [
                    'constructor' => $constructor,
                    'resolver' => $resolvers[0],
                    'config' => ['x' => 'X'],
                ],
            ],

            // #1
            [
                'args' => [$constructor, $resolvers[1], []],
                'expect' => [
                    'constructor' => $constructor,
                    'resolver' => $resolvers[1],
                    'config' => ['y' => 'Y']
                ],
            ],

            // #2
            [
                'args' => [$constructor, $resolvers[2], ['a' => 'A']],
                'expect' => [
                    'constructor' => $constructor,
                    'resolver' => $resolvers[2],
                    'config' => ['a' => 'B'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, $expect) : void
    {
        $factory = new LdapLinkFactory(...$args);

        $actualConfig = $factory->getConfig();
        $expectConfig = $expect['config'];
        foreach ([&$actualConfig, &$expectConfig] as &$config) {
            ksort($config);
        }
        $this->assertSame($expect['constructor'], $factory->getLdapLinkConstructor());
        $this->assertSame($expect['resolver'], $factory->getConfigResolver());
        $this->assertSame($expectConfig, $actualConfig);
    }

    //
    // createLdapLink()
    //

    public static function prov__createLdapLink() : array
    {
        return [
            [
                'config'    => [],
                'resolved'  => ['uri' => 'ldap:///'],
                'connect'   => ['ldap:///'],
            ],
            [
                'config'    => ['uri' => 'ldap://example.org', 'tls' => false],
                'resolved'  => ['uri' => 'ldap://example.org', 'tls' => false],
                'connect'   => ['ldap://example.org'],
            ],
            [
                'config'    => ['uri' => 'ldap://example.org', 'tls' => true],
                'resolved'  => ['uri' => 'ldap://example.org', 'tls' => true],
                'connect'   => ['ldap://example.org'],
            ],
            [
                'config'    => ['options' => ['protocol_version' => 3, 'sizelimit' => 123]],
                'resolved'  => ['uri' => 'ldap:///', 'options' => [LDAP_OPT_PROTOCOL_VERSION => 3, LDAP_OPT_SIZELIMIT => 123]],
                'connect'   => ['ldap:///'],
            ],
        ];
    }

    /**
     * @dataProvider prov__createLdapLink
     */
    public function test__createLdapLink(array $config, array $resolved, array $connect) : void
    {
        $constructor = $this->getMockBuilder(LdapLinkConstructorInterface::class)
                            ->setMethods(['connect'])
                            ->getMockForAbstractClass();
        $resolver = $this   ->getMockBuilder(LdapLinkConfigResolverInterface::class)
                            ->setMethods(['resolve'])
                            ->getMockForAbstractClass();
        $link = $this       ->getMockBuilder(LdapLinkInterface::class)
                            ->setMethods(['set_option', 'start_tls'])
                            ->getMockForAbstractClass();

        $resolver->expects($this->once())
                 ->method('resolve')
                 ->with($config)
                 ->willReturn($resolved);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with(...$connect)
                    ->willReturn($link);

        if (($resolved['options'] ?? null) !== null) {
            $options = $resolved['options'];
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

        if ($resolved['tls'] ?? false) {
            $link->expects($this->once())
                 ->method('start_tls');
        } else {
            $link->expects($this->never())
                 ->method('start_tls');
        }

        $factory = new LdapLinkFactory($constructor, $resolver, $config);

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
        $constructor = $this->getMockBuilder(LdapLinkConstructorInterface::class)
                            ->setMethods(['connect'])
                            ->getMockForAbstractClass();
        $resolver = $this   ->getMockBuilder(LdapLinkConfigResolverInterface::class)
                            ->setMethods(['resolve'])
                            ->getMockForAbstractClass();

        $link = $this->createLdapLinkMock();

        $resolver->expects($this->once())
                 ->method('resolve')
                 ->with([])
                 ->willReturn(['uri' => 'ldap:///', 'tls' => true]);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with('ldap:///')
                    ->willReturn($link);

        $link->expects($this->never())
             ->method('set_option');

        $factory = new LdapLinkFactory($constructor, $resolver, []);

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
        $resolver = $this   ->getMockBuilder(LdapLinkConfigResolverInterface::class)
                            ->setMethods(['resolve'])
                            ->getMockForAbstractClass();

        $link = $this->createLdapLinkMock();

        $link->expects($this->never())
             ->method('start_tls');

        $resolver->expects($this->once())
                 ->method('resolve')
                 ->with([])
                 ->willReturn(['uri' => 'ldap:///', 'options' => [LDAP_OPT_PROTOCOL_VERSION => 3]]);

        $constructor->expects($this->once())
                    ->method('connect')
                    ->with('ldap:///')
                    ->willReturn($link);

        $factory = new LdapLinkFactory($constructor, $resolver, []);

        $this->examineCallWithLdapTriggerError(
            [$factory, 'createLdapLink'],
            $link, 'set_option', [LDAP_OPT_PROTOCOL_VERSION, 3],
            $link,
            $config,
            $expect
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
