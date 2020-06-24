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

//use Korowai\Lib\Ldap\Adapter\ResultRecordInterface;

/**
 * Common functions for ResultEntry and ResultReference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultRecord implements ResultRecordInterface
{
//    use HasResult;
//    use HasResource;
//
//    /**
//     * Initializes the ``ResultRecord`` instance
//     *
//     * @param  resource|null $record
//     * @param  ExtLdapResultInterface $result
//     */
//    protected function initResultRecord($record, ExtLdapResultInterface $result)
//    {
//        $this->setResource($record);
//        $this->setResult($result);
//    }
//
//    // @codingStandardsIgnoreStart
//    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName
//
//    /**
//     * Get the DN of a result entry
//     *
//     * @return string|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
//     */
//    public function get_dn()
//    {
//        $ldap = $this->getLdapResult()->getLdapLink();
//
//        // PHP 7.x and earlier may return null instead of false
//        return @ldap_get_dn($ldap->getResource(), $this->getResource()) ?? false;
//    }
//
//    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
//    // @codingStandardsIgnoreEnd
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getDn() : string
//    {
//        return (string)$this->get_dn();
//    }
}

// vim: syntax=php sw=4 ts=4 et:
