<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * Provides implementation of ComparingInterface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ComparingTrait
{
    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @return LdapLinkInterface
     *
     * @psalm-mutation-free
     */
    abstract public function getLdapLink() : LdapLinkInterface;

    /**
     * Creates a compare query.
     *
     * @param  string $dn DN of the target entry
     * @param  string $attribute Target attribute
     * @param  string $value Value used for comparison
     *
     * @return CompareQueryInterface
     */
    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface
    {
        return new CompareQuery($this->getLdapLink(), $dn, $attribute, $value);
    }

    /**
     * Create compare query, execute and return its result
     *
     * @param  string $dn
     * @param  string $attribute
     * @param  string $value
     *
     * @return bool Result of the comparison
     */
    public function compare(string $dn, string $attribute, string $value) : bool
    {
        return $this->createCompareQuery($dn, $attribute, $value)->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
