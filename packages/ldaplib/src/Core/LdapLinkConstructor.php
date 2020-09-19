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

use Korowai\Lib\Ldap\Exception\ErrorException;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\exceptionErrorHandler;

/**
 * Creates new instances of LdapLink by calling LdapLink::connect().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkConstructor implements LdapLinkConstructorInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(string $host_or_uri = null, int $port = 389) : LdapLinkInterface
    {
        /** @psalm-suppress ImpureFunctionCall */
        $args = func_get_args();
        return with(exceptionErrorHandler(ErrorException::class))(function () use ($args) : LdapLinkInterface {
            if (($link = LdapLink::connect(...$args)) === false) {
                trigger_error('LdapLink::connect() returned false', E_USER_ERROR);
            }
            return $link;
        });
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
