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

use Korowai\Lib\Basic\ResourceWrapperTrait;

/**
 * Common code for LdapResultEntry and LdapResultReference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-immutable
 */
trait LdapResultItemTrait
{
    use ResourceWrapperTrait;
    use LdapResultWrapperTrait;

    /**
     * Initializes the object.
     *
     * @param resource $resource
     */
    public function __construct($resource, LdapResultInterface $ldapResult)
    {
        $this->resource = $resource;
        $this->ldapResult = $ldapResult;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     * @psalm-pure
     */
    public function supportsResourceType(string $type): bool
    {
        return 'ldap result entry' === $type;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function getLdapLink(): LdapLinkInterface
    {
        return $this->getLdapResult()->getLdapLink();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
