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
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResult implements LdapResultInterface
{
    use ResourceWrapperTrait;
    use LdapLinkWrapperTrait;

    /**
     * Initializes new LdapResult instance.
     *
     * It's assumed, that ``$resource`` was created by ``$link``. For example,
     * ``$resource = \ldap_search($link->getResource(), ...)``.
     *
     * @param resource          $resource An ldap result resource to be wrapped
     * @param LdapLinkInterface $link     An ldap link object related to the ``$resource``
     */
    public function __construct($resource, LdapLinkInterface $link)
    {
        $this->resource = $resource;
        $this->ldapLink = $link;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     * @psalm-pure
     */
    public function supportsResourceType(string $type): bool
    {
        return 'ldap result' === $type;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Count the number of entries in a search.
     *
     * @return false|int
     *
     * @see http://php.net/manual/en/function.ldap-count-entries.php ldap_count_entries()
     *
     * @psalm-mutation-free
     */
    public function count_entries()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @var null|false|int */
        $count = ldap_count_entries($ldap->getResource(), $this->getResource());

        return $count ?? false;
    }

    /**
     * Count the number of references in a search.
     *
     * @return false|int
     *
     * @see http://php.net/manual/en/function.ldap-count-references.php ldap_count_references()
     *
     * @psalm-mutation-free
     */
    public function count_references()
    {
        // as for PHP 7.x, ext-ldap does not implement count_references (libldap has it, however).
        throw new \BadMethodCallException('Not implemented');
        // $ldap = $this->getLdapLink();
        //return ldap_count_references($ldap->getResource(), $this->getResource());
    }

    /**
     * Returns result's first entry.
     *
     * @return false|LdapResultEntryInterface
     *
     * @see http://php.net/manual/en/function.ldap-first-entry.php ldap_first_entry()
     *
     * @psalm-mutation-free
     */
    public function first_entry()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @var null|false|resource */
        $res = ldap_first_entry($ldap->getResource(), $this->getResource());

        return $res ? new LdapResultEntry($res, $this) : false;
    }

    /**
     * Returns result's first reference.
     *
     * @return false|LdapResultReferenceInterface
     *
     * @see http://php.net/manual/en/function.ldap-first-reference.php ldap_first_reference()
     *
     * @psalm-mutation-free
     */
    public function first_reference()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @var null|false|resource */
        $res = ldap_first_reference($ldap->getResource(), $this->getResource());

        return $res ? new LdapResultReference($res, $this) : false;
    }

    /**
     * Free result memory.
     *
     * @see http://php.net/manual/en/function.ldap-free-result.php ldap_free_result()
     */
    public function free_result(): bool
    {
        // PHP 7.x and earlier may return null instead of false
        /** @var null|bool */
        $ret = ldap_free_result($this->getResource());

        return (bool) $ret;
    }

    /**
     * Get all result entries.
     *
     * @return array|false
     *
     * @see http://php.net/manual/en/function.ldap-get-entries.php ldap_get_entries()
     *
     * @psalm-mutation-free
     */
    public function get_entries()
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @var null|array|false */
        $entries = ldap_get_entries($ldap->getResource(), $this->getResource());

        return $entries ?? false;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function parse_result(
        &$errcode,
        &$matcheddn = null,
        &$errmsg = null,
        &$referrals = null,
        &$serverctls = null
    ): bool {
        $ldap = $this->getLdapLink();
        /** @psalm-suppress ImpureFunctionCall */
        $nargs = func_num_args();
        $args = array_slice([&$errcode, &$matcheddn, &$errmsg, &$referrals, &$serverctls], 0, $nargs);
        // PHP 7.x and earlier may return null instead of false
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var null|bool
         */
        $ret = ldap_parse_result($ldap->getResource(), $this->getResource(), ...$args);

        return (bool) $ret;
    }

    /**
     * Sort LDAP result entries on the client side.
     *
     * @see http://php.net/manual/en/function.ldap-sort.php ldap_sort()
     */
    public function sort(string $sortfilter): bool
    {
        $ldap = $this->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @var null|bool */
        $ret = ldap_sort($ldap->getResource(), $this->getResource(), $sortfilter);

        return (bool) $ret;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et tw=119:
