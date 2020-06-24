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
        $this->throwLastLdapErrorIfSet($severity, $file, $line);
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * Throws exception for last error detected, according to information
     * obtained from [ldap_errno()](https://php.net/ldap_errno)
     * and [error_get_last()](https://php.net/error_get_last).
     *
     * Throws [LdapException](\.\./\.\./Exception/LdapException.html), if
     * [$this->getLdapLink()->errno()](LdapLinkInterface.html#method_errno)
     * returns a non-zero integer. Otherwise, if
     * [error_get_last()](https://php.net/error_get_last) is not null,
     * throws [\ErrorException](https://php.net/ErrorException). If none of the
     * above conditions is meet, the method just returns void.
     *
     * @param  int $code
     *      Optional error code to be passed as second parameter to
     *      [\ErrorException::__construct()](https://www.php.net/manual/en/errorexception.construct.php).
     *
     * @return void
     *
     * @throws LdapException
     * @throws \ErrorException
     */
    public function throwLastErrorIfSet(int $code = 0) : void
    {
        $args = static::lastErrorExceptionArgs($code);
        $this->throwLastLdapErrorIfSet(...array_slice($args, 2));
        if ($args) {
            throw new \ErrorException(...$args);
        }
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
    public function throwLastLdapErrorIfSet(int $severity = null, string $file = null, int $line = null) : void
    {
        $ldapLink = $this->getLdapLink();
        $errno = $ldapLink->errno();
        if ($errno === 0 || $errno === false) {
            return;
        }
        $errstr = static::ldapErr2Str($ldapLink, $errno);
        /** @psalm-suppress ImpureFunctionCall */
        $args = func_get_args();
        throw new LdapException($errstr, $errno, ...$args);
    }

    /**
     * Returns error message for given LDAP error code.
     *
     * @param  int $errno
     *      Error code as returned by *$ldapLink->errno();*
     *
     * @return string
     *      The error message returned by [$ldapLink->err2str()](LdapLinkInterface.html#method_err2str).
     *      If the *err2str()* returns ``false``, the method returns default
     *      error message with error code and a notice about *err2str()*
     *      failure.
     */
    private static ldapErr2Str(LdapLinkInterface $ldapLink, int $errno) : string
    {
        if (($errstr = $ldapLink->err2str($errno)) === false) {
            $errstr = sprintf("error code 0x%x (error message unavailable, err2str() returned false)", $errno);
        }
        return $errstr;
    }

    /**
     * Returns an array of arguments that should be used to create instance of
     * [\ErrorException](https://www.php.net/ErrorException) for the most recent
     * PHP error obtained with [error_get_last()](https://php.net/error_get_last).
     *
     * @param  int $code
     * @return array
     */
    private static function lastErrorExceptionArgs(int $code = 0) : array
    {
        if (($last = error_get_last()) === null) {
            return [];
        }
        return [$last['message'], $code, $last['type'], $last['file'], $last['line']];
    }
}

// vim: syntax=php sw=4 ts=4 et:
