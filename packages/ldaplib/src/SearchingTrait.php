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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;

/**
 * Provides implementation of SearchingInterface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SearchingTrait
{
    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @psalm-mutation-free
     */
    abstract public function getLdapLink(): LdapLinkInterface;

    /**
     * Creates a search query.
     *
     * @param string $base_dn Base DN where the search will start
     * @param string $filter  Filter used by ldap search
     * @param array  $options Additional search options
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = []): SearchQueryInterface
    {
        return new SearchQuery($this->getLdapLink(), $base_dn, $filter, $options);
    }

    /**
     * Create search query, execute and return its result.
     *
     * @return ResultInterface Query result
     */
    public function search(string $base_dn, string $filter, array $options = []): ResultInterface
    {
        return $this->createSearchQuery($base_dn, $filter, $options)->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et:
