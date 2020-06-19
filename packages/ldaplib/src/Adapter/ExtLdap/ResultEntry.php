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

use Korowai\Lib\Ldap\Adapter\ResultEntryToEntry;
use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;

/**
 * Wrapper for ldap entry result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntry extends ResultRecord implements ExtLdapResultEntryInterface
{
    use ResultEntryToEntry;

    /** @var ResultAttributeIterator */
    private $iterator;

    public static function isLdapResultEntryResource($arg) : bool
    {
        // The name "ldap result entry" is documented: http://php.net/manual/en/resource.php
        return is_resource($arg) && (get_resource_type($arg) === "ldap result entry");
    }

    /**
     * Initializes the ``ResultEntry`` instance
     *
     * @param  resource|null $entry
     * @param  ExtLdapResultInterface $result
     */
    public function __construct($entry, ExtLdapResultInterface $result)
    {
        $this->initResultRecord($entry, $result);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return static::isLdapResultEntryResource($this->getResource());
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Return first attribute
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-first-attribute.php ldap_first_attribute()
     *
     * @psalm-suppress TypeDoesNotContainType
     * @psalm-return string|false
     */
    public function first_attribute()
    {
        $ldap = $this->getResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return @ldap_first_attribute($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Get attributes from a search result entry
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-attributes.php ldap_get_attributes()
     */
    public function get_attributes()
    {
        $ldap = $this->getResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return @ldap_get_attributes($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Get all binary values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-values-len.php ldap_get_values_len()
     */
    public function get_values_len(string $attribute)
    {
        $ldap = $this->getResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return @ldap_get_values_len($ldap->getResource(), $this->getResource(), $attribute) ?? false;
    }

    /**
     * Get all values from a result entry
     *
     * @param  string $attribute
     *
     * @return array|false
     *
     * @link http://php.net/manual/en/function.ldap-get-values.php ldap_get_values()
     */
    public function get_values(string $attribute)
    {
        $ldap = $this->getResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return @ldap_get_values($ldap->getResource(), $this->getResource(), $attribute) ?? false;
    }

    /**
     * Get the next attribute in result
     *
     * @return string|false
     *
     * @link http://php.net/manual/en/function.ldap-next-attribute.php ldap_next_attribute()
     */
    public function next_attribute()
    {
        $ldap = $this->getResult()->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        /** @psalm-suppress TypeDoesNotContainType */
        return @ldap_next_attribute($ldap->getResource(), $this->getResource()) ?? false;
    }

    /**
     * Get next result entry
     *
     * @return ResultEntry|false
     *
     * @link http://php.net/manual/en/function.ldap-next-entry.php ldap_next_entry()
     */
    public function next_entry()
    {
        $result = $this->getResult();
        $ldap = $result->getLdapLink();
        // PHP 7.x and earlier may return null instead of false
        $res = @ldap_next_entry($ldap->getResource(), $this->getResource());
        return $res ? new ResultEntry($res, $result) : false;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first attribute of the entry.
     * For subsequent calls, the method just return the iterator without
     * altering its position.
     */
    public function getAttributeIterator() : ResultAttributeIteratorInterface
    {
        if (!isset($this->iterator)) {
            // FIXME: $first may be false, shall we throw an exception then? In what circumstances?
            $first = $this->first_attribute();
            $this->iterator = new ResultAttributeIterator($this, $first === false ? null : $first);
        }
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        // FIXME: $this->get_attributes() may return false, shall we throw an exception then? In what circumstances?
        $attributes = $this->get_attributes();
        if ($attributes === false) {
        }
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
