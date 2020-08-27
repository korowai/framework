<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\exceptionErrorHandler;

/**
 * Creates and returns new instances of LdapLink.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkFactory implements LdapLinkFactoryInterface
{
    /**
     * @var array
     *
     * @psalm-readonly
     */
    private $config;

    /**
     * @var LdapLinkConstructorInterface
     *
     * @psalm-readonly
     */
    private $ldapLinkConstructor;

    /**
     * @var LdapLinkConfigResolverInterface
     *
     * @psalm-readonly
     */
    private $configResolver;

    /**
     * Creates an LdapLinkFactory
     *
     * @param  LdapLinkConstructorInterface $ldapLinkConstructor
     * @param  LdapLinkConfigResolverInterface $configResolver
     */
    public function __construct(
        LdapLinkConstructorInterface $ldapLinkConstructor,
        LdapLinkConfigResolverInterface $configResolver,
        array $config = []
    ) {
        $this->ldapLinkConstructor = $ldapLinkConstructor;
        $this->configResolver = $configResolver;
        $this->config = $this->configResolver->resolve($config);
    }

    /**
     * Returns constructor object used to create LdapLink instance.
     *
     * @return LdapLinkConstructorInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapLinkConstructor() : LdapLinkConstructorInterface
    {
        return $this->ldapLinkConstructor;
    }

    /**
     * Returns resolver object used to validate and resolve options set to this object.
     *
     * @return LdapLinkConfigResolverInterface
     *
     * @psalm-mutation-free
     */
    public function getConfigResolver() : LdapLinkConfigResolverInterface
    {
        return $this->configResolver;
    }

    /**
     * Return configuration array previously set with configure().
     *
     * If configuration is not set yet, null is returned.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function createLdapLink() : LdapLinkInterface
    {
        // FIXME: check if uri key exists and throw an error
        $link = $this->ldapLinkConstructor->connect($this->config['uri']);
        if ($this->config['tls'] ?? false) {
            $this->startTlsOnLdapLink($link);
        }
        if (($options = $this->config['options'] ?? null) !== null) {
            $this->setOptionsToLdapLink($link, $options);
        }
        return $link;
    }

    private function startTlsOnLdapLink(LdapLinkInterface $link) : void
    {
        with(new LdapLinkErrorHandler($link))(function () use ($link) : void {
            $link->start_tls();
        });
    }

    private function setOptionsToLdapLink(LdapLinkInterface $link, array $options) : void
    {
        with(new LdapLinkErrorHandler($link))(function () use ($link, $options) : void {
            foreach ($options as $id => $value) {
                $link->set_option($id, $value);
            }
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
