<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\LdapException;

/**
 * Error handler for errors originating from [LdapLink](LdapLink.html) calls.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkErrorHandler extends AbstractManagedErrorHandler implements LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;

    /**
     * Initializes the object.
     */
    public function __construct(LdapLinkInterface $ldapLink)
    {
        $this->ldapLink = $ldapLink;
    }

    /**
     * Handler implementation.
     *
     * @throws LdapException
     * @throws ErrorException
     *
     * @return never-return
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        $this->throwLastLdapErrorIfSet($severity, $message, $file, $line);

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * Create LdapLinkErrorHandler instance from LdapLinkWrapperInterface.
     */
    public static function fromLdapLinkWrapper(LdapLinkWrapperInterface $wrapper): self
    {
        return new self($wrapper->getLdapLink());
    }

    /**
     * Create LdapLinkErrorHandler instance from LdapResultWrapperInterface.
     */
    public static function fromLdapResultWrapper(LdapResultWrapperInterface $wrapper): self
    {
        return self::fromLdapLinkWrapper($wrapper->getLdapResult());
    }

    /**
     * Create LdapLinkErrorHandler from LdapResultItemWrapperInterface.
     */
    public static function fromLdapResultItemWrapper(LdapResultItemWrapperInterface $wrapper): self
    {
        return self::fromLdapResultWrapper($wrapper->getLdapResultItem());
    }

    /**
     * Throws [LdapException](\.\./\.\./Exception/LdapException.html) if
     * *$this->getLdapLink()->errno()* returns a non-zero integer; returns if
     * *errno()* returns zero or null.
     *
     * The exception is created with *$code* obtained from $this->getLdapLink()->errno()* $message
     *
     * @throws LdapException
     */
    private function throwLastLdapErrorIfSet(int $severity, string $message, string $file, int $line): void
    {
        $ldapLink = $this->getLdapLink();
        if ($ldapLink->isValid() && ($code = $ldapLink->errno())) {
            throw new LdapException($message, $code, $severity, $file, $line);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
