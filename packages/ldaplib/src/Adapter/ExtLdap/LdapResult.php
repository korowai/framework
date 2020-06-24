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
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResult implements LdapResultInterface
{
    use HasResource;
    use HasLdapLink;

    public static function isLdapResultResource($arg) : bool
    {
        // The name "ldap result" is documented: http://php.net/manual/en/resource.php
        return is_resource($arg) && (get_resource_type($arg) === "ldap result");
    }

    /**
     * Initializes new ``Result`` instance
     *
     * It's assumed, that ``$resource`` was created by ``$link``. For example,
     * ``$resource`` may be a resource returned from
     * ``\ldap_search($link->getResource(), ...)``.
     *
     * @param  resource $resource An ldap result resource to be wrapped
     * @param  LdapLinkInterface $link   An ldap link object related to the ``$resource``
     */
    public function __construct($resource, LdapLinkInterface $link)
    {
        $this->setResource($resource);
        $this->setLdapLink($link);
    }

    /**
     * Checks whether the Result represents a valid 'ldap result' resource.
     *
     * @return bool
     */
    public function isValid() : bool
    {
        return static::isLdapResultResource($this->getResource());
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Count the number of entries in a search
     *
     * @return int|bool
     *
     * @link http://php.net/manual/en/function.ldap-count-entries.php ldap_count_entries()
     */
    public function count_entries()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        return ldap_count_entries($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Count the number of references in a search
     *
     * @return int|bool
     *
     * @link http://php.net/manual/en/function.ldap-count-references.php ldap_count_references()
     */
    public function count_references()
    {
        // as for PHP 7.x, ext-ldap does not implement count_references (libldap has it, however).
        throw new \BadMethodCallException("Not implemented");
        // $ldap = $this->getLdapLink();
        //return ldap_count_references($ldap->getResource(), $this->getResource());
    }

    /**
     * Returns result's first entry
     *
     * @return LdapResultEntry|bool
     *
     * @link http://php.net/manual/en/function.ldap-first-entry.php ldap_first_entry()
     */
    public function first_entry()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        $res = ldap_first_entry($ldap->getResource(), $this->getResource());
        return $res ? new LdapResultEntry($res, $this) : false;
    }

    /**
     * Returns result's first reference
     *
     * @return LdapResultReference|bool
     *
     * @link http://php.net/manual/en/function.ldap-first-reference.php ldap_first_reference()
     */
    public function first_reference()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        $res = ldap_first_reference($ldap->getResource(), $this->getResource());
        return $res ? new LdapResultReference($res, $this) : false;
    }

    /**
     * Free result memory
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-free-result.php ldap_free_result()
     */
    public function free_result() : bool
    {
        // PHP 7.x and earlier may return null instead of false
        return ldap_free_result($this->getResource()) ?? false;
    }

    /**
     * Get all result entries
     *
     * @return array|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-entries.php ldap_get_entries()
     */
    public function get_entries()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        return ldap_get_entries($ldap->getResource(), $this->getResource()) ?? false;
    }

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
    ) : bool {
        $ldap = $this->getLdapLink();
        $args = array_slice([&$errcode, &$matcheddn, &$errmsg, &$referrals, &$serverctls], 0, func_num_args());
        // PHP 7.x and earlier may return null instead of false
        return ldap_parse_result($ldap->getResource(), $this->getResource(), ...$args) ?? false;
    }

    /**
     * Sort LDAP result entries on the client side
     *
     * @param  string $sortfilter
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-sort.php ldap_sort()
     */
    public function sort(string $sortfilter) : bool
    {
        $ldap = $this->getLdapLink();
        return ldap_sort($ldap->getResource(), $this->getResource(), $sortfilter) ?? false;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
