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

use Korowai\Lib\Ldap\Exception\LdapException;
use Korowai\Lib\Error\AbstractManagedErrorHandler;

/**
 * Error handler for errors originating from [LdapLink](LdapLink.html) calls.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkErrorHandler extends AbstractManagedErrorHandler
{
    use HasLdapLink;

    /**
     * Initializes the object.
     *
     * @param  LdapLinkInterface $ldapLink
     */
    public function __construct(LdapLinkInterface $ldapLink)
    {
        $this->setLdapLink($ldapLink);
    }

    /**
     * Handler implementation.
     *
     * @param  int $severity
     * @param  string $message
     * @param  string $file
     * @param  int $line
     *
     * @throws LdapException
     * @throws \ErrorException
     *
     * @return never-return
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        $this->throwLastLdapErrorIfSet($severity, $message, $file, $line);
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * Throws [LdapException](\.\./\.\./Exception/LdapException.html) if
     * *$this->getLdapLink()->errno()* returns a non-zero integer; returns if
     * *errno()* returns zero or null.
     *
     * The exception is created with *$code* obtained from $this->getLdapLink()->errno()* $message
     *
     * @param int $severity
     * @param string $file
     * @param int $line
     *
     * @return void
     *
     * @throws LdapException
     */
    private function throwLastLdapErrorIfSet(int $severity, string $message, string $file, int $line) : void
    {
        $ldapLink = $this->getLdapLink();
        if ($ldapLink->isValid() && ($code = $ldapLink->errno())) {
            throw new LdapException($message, $code, $severity, $file, $line);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
