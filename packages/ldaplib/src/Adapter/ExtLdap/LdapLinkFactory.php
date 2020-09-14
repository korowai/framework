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

/**
 * Produces LdapLink instances.
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
    private $constructor;

    /**
     * Creates an LdapLinkFactory using configuration array.
     *
     * @param  LdapLinkConstructorInterface $constructor
     * @param  LdapLinkConfigResolverInterfce $resolver
     * @param  array $config
     */
    public static function createWithConfig(
        LdapLinkConstructorInterface $constructor,
        LdapLinkConfigResolverInterface $resolver,
        array $config
    ) {
        $config = $resolver->resolve($config);
        return new self($constructor, $config['uri'], $config['tls'], $config['options']);
    }

    /**
     * Creates an LdapLinkFactory
     *
     * @param  LdapLinkConstructorInterface $constructor
     *      An object used to create initial LdapLink.
     * @param  string $uri
     *      The URI to connect the LdapLink to.
     * @param  bool $tls
     *      If set to true, start_tls() will be called on each newly created LdapLink.
     * @param  array $options
     *      An array of options to be set to each newly created LdapLink. It must be an array of key => value pairs
     *      with integers as keys. Each key must be a valid LDAP option identifier. $options are not validated by the
     *      constructor, though.
     */
    public function __construct(
        LdapLinkConstructorInterface $constructor,
        string $uri,
        bool $tls = false,
        array $options = []
    ) {
        $this->constructor = $constructor;
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
        return $this->constructor;
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
        $link = $this->constructor->connect($this->uri);
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
        with($link->getErrorHandler())(function () use ($link) : void {
            $link->start_tls();
        });
    }

    private function setOptionsToLdapLink(LdapLinkInterface $link, array $options) : void
    {
        with($link->getErrorHandler())(function () use ($link, $options) : void {
            foreach ($options as $id => $value) {
                $link->set_option($id, $value);
            }
        });
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
