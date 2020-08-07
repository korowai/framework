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

use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Adapter implements AdapterInterface
{
    use LdapLinkWrapperTrait;

    /**
     * @var Binding
     */
    private $binding;

    /**
     * @var EntryManager
     */
    private $entryManager;

    public function __construct(LdapLinkInterface $link)
    {
        $this->ldapLink = $link;
        $this->binding = new Binding($link);
        $this->entryManager = new EntryManager($link);
    }

    /**
     * {@inheritdoc}
     */
    public function getBinding() : BindingInterface
    {
        return $this->binding;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntryManager() : EntryManagerInterface
    {
        return $this->entryManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = []) : SearchQueryInterface
    {
        return new SearchQuery($this->getLdapLink(), $base_dn, $filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface
    {
        return new CompareQuery($this->getLdapLink(), $dn, $attribute, $value);
    }
}

// vim: syntax=php sw=4 ts=4 et:
