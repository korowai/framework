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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-immutable
 */
final class LdapLinkErrorHandler
{
    use HasLdapLink;

    /**
     * Initializes the object.
     *
     * @param  LdapLink $ldapLink
     */
    public function __construct(LdapLinkInterface $ldapLink)
    {
        $this->setLdapLink($ldapLink);
        //$this->ldapLink = $ldapLink;
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
    public function __invoke(int $severity, string $message, string $file, int $line)
    {
        $ldapLink = $this->getLdapLink();
        $errno = $ldapLink->errno();
        if ($errno === 0 || $errno === false) {
            // non-ldap error
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }
        $errstr = $ldapLink->err2str($errno);
        if ($errstr === false) {
            $errstr = sprintf("error code 0x%x (error message unavailable, err2str() returned false)", $errno);
        }
        throw new LdapException($errstr, $errno);
    }
}

// vim: syntax=php sw=4 ts=4 et:
