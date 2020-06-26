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

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapResultReferenceInterface
    extends ResourceWrapperInterface, LdapResultWrapperInterface, LdapLinkWrapperInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get the DN of the reference
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
     */
    public function get_dn();

    /**
     * Get next reference
     *
     * @return ResultReference|bool
     *
     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     */
    public function next_reference();

    /**
     * Extract information from reference entry
     *
     * @param  array|null &$referrals
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
     */
    public function parse_reference(&$referrals) : bool;

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

}

// vim: syntax=php sw=4 ts=4 et:
