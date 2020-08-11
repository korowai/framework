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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LastLdapExceptionTrait
{
    /**
     * Returns an exception for LDAP error that occured most recently.
     *
     * @return LdapException
     */
    protected static function lastLdapException(LdapLinkInterface $ldap) : LdapException
    {
        if (($errno = $ldap->errno()) === false) {
            $message = sprintf('%s::errno() returned false', get_class($ldap));
            // FIXME: return \ErrorException, or throw something, or elaborate on error code?
            return new LdapException($message, -1);
        }
        if (($errstr = LdapLink::err2str($errno)) === false) {
            $message = sprintf('%s::err2str() returned false', get_class($ldap));
            // FIXME: return \ErrorException, or throw something, or elaborate on error code?
            return new LdapException($message, -1);
        }
        return new LdapException($errstr, $errno);
    }
}

// vim: syntax=php sw=4 ts=4 et:
