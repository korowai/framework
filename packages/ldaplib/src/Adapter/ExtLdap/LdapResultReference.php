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
final class LdapResultReference implements LdapResultReferenceInterface
{
    use LdapResultItemTrait;

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get next reference
     *
     * @return LdapResultReference|false
     *
     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     *
     * @psalm-mutation-free
     */
    public function next_reference()
    {
        $result = $this->getLdapResult();
        $ldap = $result->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        $res = ldap_next_reference($ldap->getResource(), $this->getResource());
        return $res ? new LdapResultReference($res, $result) : false;
    }

    /**
     * Extract information from reference entry
     *
     * @param  array|null &$referrals
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
     *
     * @psalm-mutation-free
     */
    public function parse_reference(&$referrals) : bool
    {
        $ldap = $this->getLdapResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType
         *  @psalm-suppress PossiblyNullArgument */
        return ldap_parse_reference($ldap->getResource(), $this->getResource(), $referrals) ?? false;
    }

    /**
     * Get next result reference
     *
     * @return LdapResultReferenceInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     *
     * @psalm-mutation-free
     */
    public function next_item()
    {
        return $this->next_reference();
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et tw=120:
