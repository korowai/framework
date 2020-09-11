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
     * @var string
     *
     * @psalm-readonly
     */
    private $uri;

    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $tls;

    /**
     * @var array
     *
     * @psalm-readonly
     */
    private $options;

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
     * @param  string $uri
     * @param  bool $tls
     * @param  array $options
     */
    public function __construct(
        LdapLinkConstructorInterface $ldapLinkConstructor,
        string $uri,
        bool $tls = false,
        array $options = []
    ) {
        $this->ldapLinkConstructor = $ldapLinkConstructor;
        $this->uri = $uri;
        $this->tls = $tls;
        $this->options = $options;
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
     * Returns the $uri argument as provided to __construct().
     *
     * @return string
     *
     * @psalm-mutation-free
     */
    public function getUri() : string
    {
        return $this->uri;
    }

    /**
     * Returns the $tls argument as provided to __construct().
     *
     * @return bool
     *
     * @psalm-mutation-free
     */
    public function getTls() : bool
    {
        return $this->tls;
    }

    /**
     * Returns the $options as provided to __construct().
     *
     * If configuration is not set yet, null is returned.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function createLdapLink() : LdapLinkInterface
    {
        $link = $this->ldapLinkConstructor->connect($this->uri);
        if ($this->tls) {
            $this->startTlsOnLdapLink($link);
        }
        if ($this->options) {
            $this->setOptionsToLdapLink($link, $this->options);
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
