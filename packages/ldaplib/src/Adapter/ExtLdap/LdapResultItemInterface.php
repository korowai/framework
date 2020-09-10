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

use Korowai\Lib\Basic\ResourceWrapperInterface;

/**
 * A common interface for either ldap result entry or ldap result reference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapResultItemInterface extends
    ResourceWrapperInterface,
    LdapResultWrapperInterface,
    LdapLinkWrapperInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName
    /**
     * Get next result item in the chain
     *
     * @return LdapResultItemInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-next-entry.php ldap_next_entry()
     */
    public function next_item();
    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
