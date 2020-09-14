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

use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Basic\ResourceWrapperInterface;

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
     * @return int|false
     *
     * @link http://php.net/manual/en/function.ldap-count-entries.php ldap_count_entries()
     *
     * @psalm-mutation-free
     */
    public function count_entries();

    /**
     * Count the number of references in a search
     *
     * @return int|false
     *
     * @link http://php.net/manual/en/function.ldap-count-references.php ldap_count_references()
     *
     * @psalm-mutation-free
     */
    public function count_references();

    /**
     * Returns result's first entry
     *
     * @return LdapResultEntryInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-first-entry.php ldap_first_entry()
     *
     * @psalm-mutation-free
     */
    public function first_entry();

    /**
     * Returns result's first reference
     *
     * @return LdapResultReferenceInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-first-reference.php ldap_first_reference()
     *
     * @psalm-mutation-free
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
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-entries.php ldap_get_entries()
     *
     * @psalm-mutation-free
     */
    public function get_entries();

    /**
     * Extract information from result
     *
     * @param  mixed $errcode
     * @param  mixed $matcheddn
     * @param  mixed $errmsg
     * @param  mixed $referrals
     * @param  mixed $serverctls
     *
     * @param-out int $errcode
     * @param-out string|null $matcheddn
     * @param-out string $errmsg
     * @param-out array $referrals
     * @param-out array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-parse-result.php ldap_parse_result()
     *
     * @psalm-mutation-free
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

// vim: syntax=php sw=4 ts=4 et tw=119:
