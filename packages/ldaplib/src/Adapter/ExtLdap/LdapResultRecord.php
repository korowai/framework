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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapResultRecord
{
    use HasLdapResult;
    use HasResource;

    /**
     * Initializes the ``ResultRecord`` instance
     *
     * @param  resource $resource
     * @param  LdapResultInterface $result
     */
    protected function initResultRecord($resource, LdapResultInterface $ldapResult)
    {
        $this->setResource($resource);
        $this->setLdapResult($ldapResult);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsResourceType(string $type) : bool
    {
        return $type === 'ldap result entry';
    }

    /**
     * {@inheritdoc}
     */
    public function getLdapLink() : LdapLinkInterface
    {
        return $this->getLdapResult()->getLdapLink();
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get the DN of a result entry
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
     */
    public function get_dn()
    {
        $ldap = $this->getLdapResult()->getLdapLink();

        // PHP 7.x and earlier may return null instead of false
        return @ldap_get_dn($ldap->getResource(), $this->getResource()) ?? false;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
