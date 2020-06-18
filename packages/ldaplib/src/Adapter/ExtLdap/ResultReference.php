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

use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultReference extends ResultRecord implements ExtLdapResultReferenceInterface
{
    use LastLdapException;

    /** @var array|null */
    private $referrals;
    /** @var ResultReferralIterator */
    private $iterator;

    public static function isLdapResultReferenceResource($arg) : bool
    {
        // The name "ldap result entry" is documented: http://php.net/manual/en/resource.php
        return is_resource($arg) && (get_resource_type($arg) === "ldap result entry");
    }

    /**
     * Initializes the ``ResultReference`` instance
     *
     * @param  resource|null $reference
     * @param  ExtLdapResultInterface $result
     */
    public function __construct($reference, ExtLdapResultInterface $result)
    {
        $this->initResultRecord($reference, $result);
        $this->referrals = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return static::isLdapResultReferenceResource($this->getResource());
    }

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first attribute of the entry.
     * For subsequent calls, the method just return the iterator without
     * altering its position.
     */
    public function getReferralIterator() : ResultReferralIteratorInterface
    {
        if (!isset($this->iterator)) {
            $this->iterator = new ResultReferralIterator($this);
        }
        return $this->iterator;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get next reference
     *
     * @return ResultReference|bool
     *
     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     */
    public function next_reference()
    {
        $result = $this->getResult();
        $ldap = $result->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        $res = @ldap_next_reference($ldap->getResource(), $this->getResource());
        return $res ? new ResultReference($res, $result) : false;
    }

    /**
     * Extract information from reference entry
     *
     * @param  array|null &$referrals
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
     */
    public function parse_reference(&$referrals) : bool
    {
        $ldap = $this->getResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        return @ldap_parse_reference($ldap->getResource(), $this->getResource(), $referrals) ?? false;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * Returns referrals
     * @return array
     * @throws LdapException thrown when ``parse_reference`` returns ``false``
     */
    public function getReferrals() : array
    {
        if (!isset($this->referrals)) {
            if ($this->parse_reference($referrals) === false) {
                throw static::lastLdapException($this->getResult()->getLdapLink());
            }
            $this->referrals = $referrals;
        }
        return $this->referrals;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns the result of ``current($this->referrals)``.
     */
    public function referrals_current()
    {
        return current($this->getReferrals());
    }

    /**
     * Returns the result of ``key($this->referrals)``.
     */
    public function referrals_key()
    {
        return key($this->getReferrals());
    }

    /**
     * Returns the result of ``next($this->referrals)``.
     */
    public function referrals_next()
    {
        $this->getReferrals();
        return next($this->referrals);
    }

    /**
     * Returs the result of ``reset($this->referrals)``.
     */
    public function referrals_reset()
    {
        $this->getReferrals();
        return reset($this->referrals);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
