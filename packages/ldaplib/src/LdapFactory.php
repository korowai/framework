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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfig;

/**
 * Abstract base class for Adapter factories.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapFactory implements LdapFactoryInterface
{
    /**
     * @var LdapLinkFactoryInterface
     *
     * @psalm-readonly
     */
    private $ldapLinkFactory;

    /**
     * @var LdapLinkConfigResolverInterface
     *
     * @psalm-readonly
     */
    private $ldapLinkConfigResolver;

    /**
     * Creates an LdapFactory
     *
     * @param  LdapLinkFactoryInterface $ldapLinkFactory
     */
    public function __construct(
        LdapLinkFactoryInterface $ldapLinkFactory,
        LdapLinkConfigResolverInterface $ldapLinkConfigResolver
    ) {
        $this->ldapLinkFactory = $ldapLinkFactory;
        $this->ldapLinkConfigResolver = $ldapLinkConfigResolver;
    }

    /**
     * Returns the encapsulated LdapLinkFactoryInterface.
     *
     * @return LdapLinkFactoryInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapLinkFactory() : LdapLinkFactoryInterface
    {
        return $this->ldapLinkFactory;
    }

    /**
     * Returns the encapsulated LdapLinkConfigResolverInterface.
     *
     * @return LdapLinkConfigResolverInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapLinkConfigResolver() : LdapLinkConfigResolverInterface
    {
        return $this->ldapLinkConfigResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function createLdapInterface(array $config) : LdapInterface
    {
        $config = LdapLinkConfig::fromArray($this->ldapLinkConfigResolver, $config);
        $link = $this->ldapLinkFactory->createLdapLink($config);
        return new Ldap($link);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
