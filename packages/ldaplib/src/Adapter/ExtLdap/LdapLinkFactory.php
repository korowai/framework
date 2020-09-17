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
     * @var LdapLinkConstructorInterface
     *
     * @psalm-readonly
     */
    private $constructor;

    /**
     * Initializes the object.
     *
     * @param  LdapLinkConstructorInterface $constructor
     *      An object used to create initial LdapLink.
     */
    public function __construct(LdapLinkConstructorInterface $constructor)
    {
        $this->constructor = $constructor;
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
     * {@inheritdoc}
     */
    public function createLdapLink(LdapLinkConfigInterface $config) : LdapLinkInterface
    {
        $link = $this->constructor->connect($config->uri());
        if ($config->tls()) {
            $this->startTlsOnLdapLink($link);
        }
        $this->setOptionsToLdapLink($link, $config->options());
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
