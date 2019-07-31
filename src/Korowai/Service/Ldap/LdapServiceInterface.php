<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap;

use Korowai\Component\Ldap\LdapInterface;
use Korowai\Component\Ldap\Adapter\AdapterInterface;
use Korowai\Component\Ldap\Adapter\BindingInterface;
use Korowai\Component\Ldap\Adapter\EntryManagerInterface;
use Korowai\Component\Ldap\Adapter\QueryInterface;
use Korowai\Component\Ldap\Adapter\ResultInterface;
use Korowai\Component\Ldap\Entry;

/**
 * LDAP Service interface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapServiceInterface
{
    /**
     * Configure the service.
     *
     * @param array $config
     */
    public function configure(array $config);

    /**
     * Return the whole config (resolved).
     *
     * @return array
     */
    public function getConfig() : array;

    /**
     * Get an array of valid IDs.
     *
     * @return array
     */
    public function getDatabaseIds() : array;

    /**
     * Get database meta information.
     *
     * @return array
     */
    public function getDatabaseConfig(int $id) : array;

    /**
     * Get database meta information.
     *
     * @return array
     */
    public function getDatabaseMeta(int $id) : array;

    /**
     * Get Ldap instance with given ID. The instance is created if not exists.
     *
     * @param int $id
     * @return \Korowai\Component\Ldap\LdapInterface|null
     */
    public function getLdap(int $id) : LdapInterface;

    /**
     * Delete Ldap instance with given ID.
     *
     * @param int $id
     */
    public function unsetLdap(int $id);

    /**
     * Returns LDAP adapter
     *
     * @param int $id LDAP database identifier
     * @return AdapterInterface Adapter
     */
    public function getAdapter(int $id) : AdapterInterface;

    /**
     * Check whether the connection was already bound or not.
     *
     * @return bool
     */
    public function isBound(int $id) : bool;

    /**
     * Binds the connection against a DN and password
     *
     * @param int $id           LDAP database identifier
     * @param string $dn        The user's DN
     * @param string $password  The associated password
     */
    public function bind(int $id, string $dn = null, string $password = null);

    /**
     * Unbinds the connection
     *
     * @param int $id LDAP database identifier
     */
    public function unbind(int $id);

    /**
     * Adds a new entry in the LDAP server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     */
    public function add(int $id, Entry $entry);

    /**
     * Updates entry in Ldap server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     * @param string $newRdn
     * @param bool $deleteOldRdn
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     */
    public function rename(int $id, Entry $entry, string $newRdn, bool $deleteOldRdn = true);

    /**
     * Renames an entry on the LDAP server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     */
    public function update(int $id, Entry $entry);

    /**
     * Removes entry from the Ldap server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     */
    public function delete(int $id, Entry $entry);

    /**
     * Create query, execute and return its result
     *
     * @param int $id
     * @param string $base_dn
     * @param string $filter
     * @param array $options
     *
     * @return ResultInterface Query result
     */
    public function query(int $id, string $base_dn, string $filter, array $options = array()) : ResultInterface;

    /**
     * Returns the current binding object.
     *
     * @param int $id LDAP database identifier
     *
     * @return \Korowai\Component\Ldap\Adapter\BindingInterface
     */
    public function getBinding(int $id) : BindingInterface;

    /**
     * Returns the current entry manager.
     *
     * @param int $id LDAP database identifier
     *
     * @return \Korowai\Component\Ldap\Adapter\EntryManagerInterface
     */
    public function getEntryManager(int $id) : EntryManagerInterface;

    /**
     * Creates a search query
     *
     * @param int $id LDAP database identifier
     * @param string $base_dn Base DN where the search will start
     * @param string $filter Filter used by ldap search
     * @param array $options Additional search options
     *
     * @return \Korowai\Component\Ldap\Adapter\EntryManagerInterface
     */
    public function createQuery(int $id, string $base_dn, string $filter, array $options = array()) : QueryInterface;
}

// vim: syntax=php sw=4 ts=4 et:
