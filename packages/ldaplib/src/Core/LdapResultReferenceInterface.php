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

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapResultReferenceInterface extends LdapResultItemInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get next reference.
     *
     * @return false|LdapResultReference
     *
     * @see http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     *
     * @psalm-mutation-free
     */
    public function next_reference();

    /**
     * Extract information from reference entry.
     *
     * @param null|array &$referrals
     *
     * @see http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
     *
     * @psalm-mutation-free
     */
    public function parse_reference(&$referrals): bool;

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et tw=119:
