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
 * Wrapper for ldap entry result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapResultEntryInterface extends LdapResultItemInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get the DN of the entry
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
     *
     * @psalm-mutation-free
     */
    public function get_dn();

    /**
     * Return first attribute
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-first-attribute.php ldap_first_attribute()
     *
     * @psalm-mutation-free
     */
    public function first_attribute();

    /**
     * Get attributes from a search result entry
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-attributes.php ldap_get_attributes()
     *
     * @psalm-mutation-free
     */
    public function get_attributes();

    /**
     * Get all binary values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-values-len.php ldap_get_values_len()
     *
     * @psalm-mutation-free
     */
    public function get_values_len(string $attribute);

    /**
     * Get all values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-values.php ldap_get_values()
     */
    public function get_values(string $attribute);

    /**
     * Get the next attribute in result
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-next-attribute.php ldap_next_attribute()
     *
     * @psalm-mutation-free
     */
    public function next_attribute();

    /**
     * Get next result entry
     *
     * @return LdapResultEntryInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-next-entry.php ldap_next_entry()
     *
     * @psalm-mutation-free
     */
    public function next_entry();

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
