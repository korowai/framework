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

use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryToEntry;
use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;
use function Korowai\Lib\Context\with;

/**
 * Wrapper for ldap entry result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntry /*extends ResultRecord*/ implements ResultEntryInterface
{
//    use ResultEntryToEntry;

    /** @var ResultAttributeIterator */
    private $iterator;

    /** @var LdapResultEntryInterface */
    private $ldapResultEntry;

    /**
     * Initializes the object
     *
     * @param  LdapEntryInterface $ldapEntry
     */
    public function __construct(LdapResultEntryInterface $ldapResultEntry)
    {
        $this->setLdapResultEntry($ldapResultEntry);
    }

    /**
     * Sets an instance of LdapResultEntryInterface to this object.
     *
     * @param  LdapResultEntryInterface $ldapResultEntry
     * @return void
     */
    private function setLdapResultEntry(LdapResultEntryInterface $ldapResultEntry) : void
    {
        $this->ldapResultEntry = $ldapResultEntry;
    }

    /**
     * Returns the LdapResultEntryInterface instance encapsulated by this
     * object.
     *
     * @return LdapResultEntryInterface
     */
    public function getLdapResultEntry() : LdapResultEntryInterface
    {
        return $this->ldapResultEntry;
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
            $ldapResultEntry = $this->getLdapResultEntry();
            $ldap = $ldapResultEntry->getLdapResult()->getLdapLink();
            /** @var string|false */
            $first = with(new LdapLinkErrorHandler($ldap))(function ($eh) use ($ldapResultEntry) {
                return $ldapResultEntry->first_attribute();
            });
            $this->iterator = new ResultAttributeIterator($ldapResultEntry, $first === false ? null : $first);
        }
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        $ldapResultEntry = $this->getLdapResultEntry();
        $ldap = $ldapResultEntry->getLdapResult()->getLdapLink();
        /** @var array */
        $attributes = with(new LdapLinkErrorHandler($ldap))(function ($eh) {
            return $this->get_attributes();
        });
        return static::cleanupAttributes($attributes);
    }

    private static function cleanupAttributes(array $attributes) : array
    {
        $attributes = array_filter($attributes, function ($key) {
            return is_string($key) && ($key != "count");
        }, ARRAY_FILTER_USE_KEY);
        array_walk($attributes, function (&$value) {
            unset($value['count']);
        });
        return array_change_key_case($attributes, CASE_LOWER);
    }
}

// vim: syntax=php sw=4 ts=4 et:
