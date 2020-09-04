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

//use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\OptionsResolver\Options;

//use Korowai\Lib\Ldap\Binding;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManager;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolver;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
//use function Korowai\Lib\Context\with;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;

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
     * Creates an LdapFactory
     *
     * @param  LdapLinkFactoryInterface $ldapLinkFactory
     */
    public function __construct(LdapLinkFactoryInterface $ldapLinkFactory)
    {
        $this->ldapLinkFactory = $ldapLinkFactory;
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
     * Creates and returns new instance of LdapInterface.
     *
     * @return LdapInterface
     */
    public function createLdapInterface() : LdapInterface
    {
        $link = $this->ldapLinkFactory->createLdapLink();
        return new Ldap($link);
    }
}

// vim: syntax=php sw=4 ts=4 et:
