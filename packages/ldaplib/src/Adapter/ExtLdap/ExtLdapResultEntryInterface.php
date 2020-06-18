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

use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;

/**
 * Wrapper for ldap entry result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ExtLdapResultEntryInterface extends ResultEntryInterface, ResourceWrapperInterface, ResultWrapperInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Return first attribute
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-first-attribute.php ldap_first_attribute()
     */
    public function first_attribute();

    /**
     * Get attributes from a search result entry
     *
     * @return array|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-attributes.php ldap_get_attributes()
     */
    public function get_attributes();

    /**
     * Get all binary values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-values-len.php ldap_get_values_len()
     */
    public function get_values_len(string $attribute);

    /**
     * Get all values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-values.php ldap_get_values()
     */
    public function get_values(string $attribute);

    /**
     * Get the next attribute in result
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-next-attribute.php ldap_next_attribute()
     */
    public function next_attribute();

    /**
     * Get next result entry
     *
     * @return ResultEntry|bool
     *
     * @link http://php.net/manual/en/function.ldap-next-entry.php ldap_next_entry()
     */
    public function next_entry();

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
