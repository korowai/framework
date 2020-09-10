<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\EntryManagerTrait;

use InvalidArgumentException;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Ldap implements LdapInterface, LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;
    use BindingTrait;
    use ComparingTrait;
    use SearchingTrait;
    use EntryManagerTrait;

    /**
     * Returns new Ldap instance configured with config.
     *
     * @param  array $config
     * @param  LdapLinkConstructor $ldapLinkConstructor
     * @param  LdapLinkConfigResolverInterface $ldapLinkConfigResolver
     *
     * @return Ldap
     */
    public static function createWithConfig(
        array $config,
        LdapLinkConstructorInterface $ldapLinkConstructor = null,
        LdapLinkConfigResolverInterface $configResolver = null
    ) : self {
        if ($ldapLinkConstructor === null) {
            $ldapLinkConstructor = new LdapLinkConstructor;
        }
        if ($configResolver === null) {
            $configResolver = new LdapLinkConfigResolver;
        }
        $factory = new LdapLinkFactory($ldapLinkConstructor, $configResolver, $config);
        return self::createWithLdapLinkFactory($factory);
    }

    /**
     * Returns new Ldap instance with adapter created by *$factory*.
     *
     * @param  LdapLinkFactoryInterface $ldapLinkFactory
     * @return Ldap
     */
    public static function createWithLdapLinkFactory(LdapLinkFactoryInterface $ldapLinkFactory) : self
    {
        return new self($ldapLinkFactory->createLdapLink());
    }

    /**
     * Create new Ldap instance
     *
     * @param LdapLinkInterface $ldapLink
     * @param bool $bound
     */
    public function __construct(LdapLinkInterface $ldapLink, bool $bound = false)
    {
        $this->ldapLink = $ldapLink;
        $this->bound = $bound;
    }
}

// vim: syntax=php sw=4 ts=4 et:
