<?php
/**
 * @file src/Korowai/Lib/Ldap/EntryInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * Represents single ldap entry with DN and attributes
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface EntryInterface
{
    /**
     * Retuns the entry's DN.
     *
     * @return string
     */
    public function getDn() : string;

    /**
     * Sets the entry's DN.
     *
     * @param string $dn
     * @throws \TypeError
     */
    public function setDn(string $dn);


    /**
     * Returns the complete array of attributes
     *
     * @return array
     */
    public function getAttributes() : array;

    /**
     * Returns a specific attribute's values
     *
     * @param string $name
     *
     * @return array
     */
    public function getAttribute(string $name) : array;

    /**
     * Retuns whether an attribute exists.
     *
     * @return bool
     */
    public function hasAttribute(string $name) : bool;

    /**
     * Sets attributes.
     *
     * For each attribute in $attributes, if attribute already exists in Entry,
     * its values will be replaced with values provided in $attributes. If
     * there is no attribute in Entry, it'll be added to Entry.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * Sets values for the given attribute
     *
     * @param string $name
     * @param array $values
     */
    public function setAttribute(string $name, array $values);
}

// vim: syntax=php sw=4 ts=4 et:
