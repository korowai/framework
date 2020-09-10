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

/**
 * Iterates through an ldap result entry attributes.
 *
 * Only one instance of ``ResultAttributeIterator`` should be used for a given
 * LdapResultEntryInterface. The internal state (position) of the iterator is
 * managed by the "ldap entry" resource encapsulated by ``ResultEntry`` object
 * which is provided as ``$entry`` argument to
 * ``ResultAttributeIterator::__construct()``. This is a consequence of how
 * PHP ldap extension implements attribute iteration &mdash; the ``berptr``
 * argument to ``libldap`` functions
 * [ldap_first_attribute (3)](https://manpages.debian.org/stretch-backports/libldap2-dev/ldap_first_attribute.3.en.html)
 * and
 * [ldap_next_attribute (3)](https://manpages.debian.org/stretch-backports/libldap2-dev/ldap_next_attribute.3.en.html)
 * is stored by PHP ldap extension in an ``"ldap entry"`` resource and is
 * inaccessible for user.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultAttributeIterator implements ResultAttributeIteratorInterface, LdapResultEntryWrapperInterface
{
    use LdapResultEntryWrapperTrait;

    /** @var string|null */
    private $attribute;

    /**
     * Initializes the ``ResultAttributeIterator``.
     *
     * The ``$attribute`` should be a valid attribute name returned by either
     * ``$entry->first_attribute()`` or ``$entry->next_attribute()`` or
     * it should be null.
     *
     * @param  LdapResultEntryInterface $ldapResultEntry An ldap entry containing the attributes
     * @param  string|null $attribute Name of the current attribute pointed to by Iterator
     */
    public function __construct(LdapResultEntryInterface $ldapResultEntry, string $attribute = null)
    {
        $this->ldapResultEntry = $ldapResultEntry;
        $this->attribute = is_string($attribute) ? strtolower($attribute) : $attribute;
    }

    /**
     * Returns an array of values of the current attribute.
     *
     * Should only be used on valid iterator.
     *
     * @return array an array of values of the current attribute.
     * @link http://php.net/manual/en/iterator.current.php Iterator::current
     */
    public function current()
    {
        $entry = $this->getLdapResultEntry();
        // FIXME: with(new LdapLinkErrorHandler(...)) ...
        $values = $entry->get_values($this->attribute);
        unset($values['count']);
        return $values;
    }

    /**
     * Returns the key of the current element (name of current attribute).
     * @return string|null The name of current attribute or ``null`` if the
     *         iterator is invalid (past the end).
     *
     * @link http://php.net/manual/en/iterator.key.php Iterator::key
     */
    public function key()
    {
        return $this->attribute;
    }

    /**
     * Moves the current position to the next element
     *
     * @link http://php.net/manual/en/iterator.next.php Iterator::next
     */
    public function next()
    {
        $entry = $this->getLdapResultEntry();
        // FIXME: with(new LdapLinkErrorHandler(...)) ...
        $next = $entry->next_attribute();
        $this->attribute = is_string($next) ? strtolower($next) : $next;
    }

    /**
     * Rewinds back to the first element of the iterator
     *
     * @link http://php.net/manual/en/iterator.rewind.php Iterator::rewind
     */
    public function rewind()
    {
        $entry = $this->getLdapResultEntry();
        // FIXME: with(new LdapLinkErrorHandler(...)) ...
        $first = $entry->first_attribute();
        $this->attribute = is_string($first) ? strtolower($first) : $first;
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php Iterator::valid
     */
    public function valid()
    {
        return is_string($this->attribute);
    }
}

// vim: syntax=php sw=4 ts=4 et: