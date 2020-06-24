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

use Korowai\Lib\Ldap\Adapter\ResultInterface;

/**
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapResultInterface extends ResourceWrapperInterface, LdapLinkWrapperInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Count the number of entries in a search
     *
     * @return int|bool
     *
     * @link http://php.net/manual/en/function.ldap-count-entries.php ldap_count_entries()
     */
    public function count_entries();

    /**
     * Count the number of references in a search
     *
     * @return int|bool
     *
     * @link http://php.net/manual/en/function.ldap-count-references.php ldap_count_references()
     */
    public function count_references();

    /**
     * Returns result's first entry
     *
     * @return ResultEntry|bool
     *
     * @link http://php.net/manual/en/function.ldap-first-entry.php ldap_first_entry()
     */
    public function first_entry();

    /**
     * Returns result's first reference
     *
     * @return ResultReference|bool
     *
     * @link http://php.net/manual/en/function.ldap-first-reference.php ldap_first_reference()
     */
    public function first_reference();

    /**
     * Free result memory
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-free-result.php ldap_free_result()
     */
    public function free_result() : bool;

    /**
     * Get all result entries
     *
     * @return array|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-entries.php ldap_get_entries()
     */
    public function get_entries();

    /**
     * Extract information from result
     *
     * @param  int|null &$errcode
     * @param  string|null $matcheddn
     * @param  string|null $errmsg
     * @param  array|null $referrals
     * @param  array|null $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-parse-result.php ldap_parse_result()
     */
    public function parse_result(
        &$errcode,
        &$matcheddn = null,
        &$errmsg = null,
        &$referrals = null,
        &$serverctls = null
    ) : bool;

    /**
     * Sort LDAP result entries on the client side
     *
     * @param  string $sortfilter
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-sort.php ldap_sort()
     */
    public function sort(string $sortfilter) : bool;

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
