<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ExtLdap/Result.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\AbstractResult;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Result extends AbstractResult
{
    /** @var resource */
    private $result;
    /** @var LdapLink */
    private $link;

    public static function isLdapResultResource($arg) : bool
    {
        // The name "ldap result" is documented: http://php.net/manual/en/resource.php
        return is_resource($arg) && (get_resource_type($arg) === "ldap result");
    }

    /**
     * Initializes new ``Result`` instance
     *
     * It's assumed, that ``$result`` was created by ``$link``. For example,
     * ``$result`` may be a resource returned from
     * ``\ldap_search($link->getResource(), ...)``.
     *
     * @param resource $result An ldap result resource to be wrapped
     * @param LdapLink $link   An ldap link object related to the ``$result``
     */
    public function __construct($result, LdapLink $link)
    {
        $this->result = $result;
        $this->link = $link;
    }

    /**
     * Destructs Result
     */
    public function __destruct()
    {
        if ($this->isValid()) {
            $this->free_result();
        }
    }

    /**
     * Checks whether the Result represents a valid 'ldap result' resource.
     */
    public function isValid() : bool
    {
        return static::isLdapResultResource($this->result);
    }

    /**
     * Returns the ``$link`` provided to ``__construct()`` at construction time
     * @return LdapLink The ``$link`` provided to ``__construct()`` at construction time
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Returns the ``$result`` provided to ``__construct()`` at construction time
     * @return resource The ``$result`` provided to ``__construct()`` at construction time
     */
    public function getResource()
    {
        return $this->result;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Retrieve the LDAP pagination cookie
     *
     * @link http://php.net/manual/en/function.ldap-control-paged-result-response.php
     *       ldap_control_paged_result_response()
     */
    public function control_paged_result_response(&...$args)
    {
        return $this->link->control_paged_result_response($this, ...$args);
    }

    /**
     * Count the number of entries in a search
     *
     * @link http://php.net/manual/en/function.ldap-count-entries.php ldap_count_entries()
     */
    public function count_entries()
    {
        return $this->link->count_entries($this);
    }

    /**
     * Return first result id
     *
     * @link http://php.net/manual/en/function.ldap-first-entry.php ldap_first_entry()
     */
    public function first_entry()
    {
        return $this->link->first_entry($this);
    }

    /**
     * Count the number of references in a search
     *
     * @link http://php.net/manual/en/function.ldap-count-references.php ldap_count_references()
     */
    public function count_references()
    {
        return $this->link->count_references($this);
    }

    /**
     * Return first reference
     *
     * @link http://php.net/manual/en/function.ldap-first-reference.php ldap_first_reference()
     */
    public function first_reference()
    {
        return $this->link->first_reference($this);
    }

    /**
     * Free result memory
     *
     * @link http://php.net/manual/en/function.ldap-free-result.php ldap_free_result()
     */
    public function free_result()
    {
        return LdapLink::free_result($this);
    }

    /**
     * Get all result entries
     *
     * @link http://php.net/manual/en/function.ldap-get-entries.php ldap_get_entries()
     */
    public function get_entries()
    {
        return $this->link->get_entries($this);
    }

    /**
     * Extract information from result
     *
     * @link http://php.net/manual/en/function.ldap-parse-result.php ldap_parse_result()
     */
    public function parse_result(&$errcode, &...$tail)
    {
        return $this->link->parse_result($this, $errcode, ...$tail);
    }

    /**
     * Sort LDAP result entries on the client side
     *
     * @link http://php.net/manual/en/function.ldap-sort.php ldap_sort()
     */
    public function sort(string $sortfilter)
    {
        return $this->link->sort($this, $sortfilter);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * {@inheritdoc}
     */
    public function getResultEntries() : array
    {
        return iterator_to_array($this->getResultEntryIterator(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferences() : array
    {
        return iterator_to_array($this->getResultReferenceIterator(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface
    {
        $first = $this->first_entry();
        return new ResultEntryIterator($this, $first === false ? null : $first);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface
    {
        $first = $this->first_reference();
        return new ResultReferenceIterator($this, $first === false ? null : $first);
    }
}

// vim: syntax=php sw=4 ts=4 et:
