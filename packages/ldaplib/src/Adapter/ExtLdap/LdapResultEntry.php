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

use function Korowai\Lib\Context\with;

/**
 * Wrapper for ldap entry result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultEntry implements LdapResultEntryInterface
{
    use LdapResultItem;

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns first result entry in the message chain.
     *
     * @return LdapResultItemInterface|false
     */
    public function first_item()
    {
        return $result->getLdapResult()->first_entry();
    }

    /**
     * Returns next result entry in the message chain.
     *
     * @return LdapResultItemInterface|false
     */
    public function next_item()
    {
        return $this->next_entry();
    }

    /**
     * Get the DN of a result entry
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
     */
    public function get_dn()
    {
        $ldap = $this->getLdapResult()->getLdapLink();

        // PHP 7.x and earlier may return null instead of false
        return @ldap_get_dn($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Return first attribute
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-first-attribute.php ldap_first_attribute()
     *
     * @psalm-suppress TypeDoesNotContainType
     * @psalm-return string|false
     */
    public function first_attribute()
    {
        $ldap = $this->getLdapResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return ldap_first_attribute($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Get attributes from a search result entry
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-attributes.php ldap_get_attributes()
     */
    public function get_attributes()
    {
        $ldap = $this->getLdapResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return ldap_get_attributes($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Get all binary values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-values-len.php ldap_get_values_len()
     */
    public function get_values_len(string $attribute)
    {
        $ldap = $this->getLdapResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return ldap_get_values_len($ldap->getResource(), $this->getResource(), $attribute) ?? false;
    }

    /**
     * Get all values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-values.php ldap_get_values()
     */
    public function get_values(string $attribute)
    {
        $ldap = $this->getLdapResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return ldap_get_values($ldap->getResource(), $this->getResource(), $attribute) ?? false;
    }

    /**
     * Get the next attribute in result
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-next-attribute.php ldap_next_attribute()
     */
    public function next_attribute()
    {
        $ldap = $this->getLdapResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return ldap_next_attribute($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Get next result entry
     *
     * @return LdapResultEntryInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-next-entry.php ldap_next_entry()
     */
    public function next_entry()
    {
        $result = $this->getLdapResult();
        $ldap = $result->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        $res = ldap_next_entry($ldap->getResource(), $this->getResource());
        return $res ? new LdapResultEntry($res, $result) : false;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
