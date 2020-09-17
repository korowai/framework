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

use Symfony\Component\OptionsResolver\Exception\ExceptionInterface as OptionsResolverException;

/**
 * Produces LdapLink instances.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkConfig implements LdapLinkConfigInterface
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
     * Creates an LdapLinkConfig using configuration array.
     *
     * @param  LdapLinkConstructorInterface $constructor
     * @param  LdapLinkConfigResolverInterface $resolver
     * @param  array $config
     *
     * @return self
     * @throws OptionsResolverException
     */
    public static function fromArray(LdapLinkConfigResolverInterface $resolver, array $config) : self
    {
        $config = $resolver->resolve($config);
        return new self($config['uri'], $config['tls'], $config['options']);
    }

    /**
     * Initializes the object.
     *
     * @param  string $uri
     *      The URI to connect the LdapLink to.
     * @param  bool $tls
     *      If set to true, start_tls() will be called on each newly created LdapLink.
     * @param  array $options
     *      An array of options to be set to each newly created LdapLink. It must be an array of key => value pairs
     *      with integers as keys. Each key must be a valid LDAP option identifier. $options are not validated by the
     *      constructor, though.
     */
    private function __construct(string $uri, bool $tls, array $options)
    {
        $this->uri = $uri;
        $this->tls = $tls;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function uri() : string
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function tls() : bool
    {
        return $this->tls;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function options() : array
    {
        return $this->options;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
