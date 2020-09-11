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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Exception\LdapException;

use function Korowai\Lib\Context\with;

/**
 * Provides implementation of BindingInterface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingTrait
{
    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @return LdapLinkInterface
     *
     * @psalm-mutation-free
     */
    abstract public function getLdapLink() : LdapLinkInterface;

    /** @var bool */
    private $bound;

    /**
     * {@inheritdoc}
     */
    public function isBound() : bool
    {
        return $this->bound;
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $dn = null, string $password = null) : bool
    {
        $args = func_get_args();
        $link = $this->getLdapLink();
        try {
            with(new LdapLinkErrorHandler($link))(function () use ($link, $args) {
                $this->bound = $link->bind(...$args);
            });
        } catch (LdapException $exception) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            // There is a special case in ext-ldap's ldap_bind() for DN or
            // Password containing a null byte. In this case, the function sets
            // ldap error code 49 (Invalid Credentials) and triggers error with
            // message "... contains a null byte". The actual call to
            // OpenLDAP's ldap_bind() is not made, so the state of ldap link
            // resource remains unaltered. This means, an already bound ldap
            // link remains bound.
            if ($code !== 49 || preg_match('/(DN|Password) contains a null byte/', $message) === 0) {
                $this->bound = false;
            }
            throw $exception;
        }
        return $this->bound;
    }

    /**
     * Unbinds the link
     *
     * After unbind the connection is no longer valid (and useful)
     *
     * @throws LdapException
     */
    public function unbind() : void
    {
        $link = $this->getLdapLink();
        $result = with(new LdapLinkErrorHandler($link))(function () use ($link) : bool {
            return $link->unbind();
        });
        // the state can only change to false and only when unbind is successful
        if ($result) {
            $this->bound = false;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
