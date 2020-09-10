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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ResultEntryToEntry;
use function Korowai\Lib\Context\with;

/**
 * Wrapper for ldap entry result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntry implements ResultEntryInterface, LdapResultEntryWrapperInterface
{
    use LdapResultEntryWrapperTrait;
    use ResultEntryToEntry;

    /** @var ResultAttributeIteratorInterface|null */
    private $iterator;

    /**
     * Initializes the object
     *
     * @param  LdapEntryInterface $ldapEntry
     */
    public function __construct(LdapResultEntryInterface $ldapResultEntry)
    {
        $this->ldapResultEntry = $ldapResultEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getDn() : string
    {
        $entry = $this->getLdapResultEntry();

        /** @var string|false */
        $dn = with(LdapLinkErrorHandler::fromLdapResultWrapper($entry))(function () use ($entry) {
            return $entry->get_dn();
        });

        return (string)$dn;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        $entry = $this->getLdapResultEntry();

        /** @var array|false */
        $attributes = with(LdapLinkErrorHandler::fromLdapResultWrapper($entry))(function () use ($entry) {
            return $entry->get_attributes();
        });

        return static::cleanupAttributes($attributes === false ? [] : $attributes);
    }

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first attribute of the entry.
     * For subsequent calls, the method just return the iterator without
     * altering its position.
     */
    public function getAttributeIterator() : ResultAttributeIteratorInterface
    {
        if (!isset($this->iterator)) {
            $entry = $this->getLdapResultEntry();

            /** @var string|false */
            $first = with(LdapLinkErrorHandler::fromLdapResultWrapper($entry))(function () use ($entry) {
                return $entry->first_attribute();
            });

            $this->iterator = new ResultAttributeIterator($entry, $first === false ? null : $first);
        }
        return $this->iterator;
    }

    /**
     * Returns iterator over entry's attributes.
     *
     * @return ResultAttributeIteratorInterface
     */
    public function getIterator() : ResultAttributeIteratorInterface
    {
        return $this->getAttributeIterator();
    }

    private static function cleanupAttributes(array $attributes) : array
    {
        $attributes = array_filter(
            $attributes,
            /** @psalm-param mixed $key */
            function ($key) {
                return is_string($key) && ($key !== 'count');
            },
            ARRAY_FILTER_USE_KEY
        );
        array_walk($attributes, function (array &$values) {
            unset($values['count']);
        });
        return array_change_key_case($attributes, CASE_LOWER);
    }
}

// vim: syntax=php sw=4 ts=4 et:
