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

abstract class AbstractLdapService implements LdapServiceInterface
{
    /**
     * Returns LDAP adapter
     *
     * @param int $id LDAP database identifier
     * @return AdapterInterface Adapter
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function getAdapter(int $id) : AdapterInterface
    {
        return $this->getLdap($id)->getAdapter();
    }

    /**
     * Check whether the connection was already bound or not.
     *
     * @return bool
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function isBound(int $id) : bool
    {
        return $this->getLdap($id)->isBound();
    }

    /**
     * Binds the connection against a DN and password
     *
     * @param int $id           LDAP database identifier
     * @param string $dn        The user's DN
     * @param string $password  The associated password
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function bind(int $id, string $dn = null, string $password = null)
    {
        return $this->getLdap($id)->bind(...array_slice(func_get_args(),1));
    }

    /**
     * Unbinds the connection
     *
     * @param int $id LDAP database identifier
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function unbind(int $id)
    {
        return $this->getLdap($id)->unbind();
    }

    /**
     * Adds a new entry in the LDAP server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function add(int $id, Entry $entry)
    {
        return $this->getLdap($id)->add($entry);
    }

    /**
     * Updates entry in Ldap server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     * @param string $newRdn
     * @param bool $deleteOldRdn
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function rename(int $id, Entry $entry, string $newRdn, bool $deleteOldRdn = true)
    {
        return $this->getLdap($id)->rename($entry, $newRdn, $deleteOldRdn);
    }

    /**
     * Renames an entry on the LDAP server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function update(int $id, Entry $entry)
    {
        return $this->getLdap($id)->update($entry);
    }

    /**
     * Removes entry from the Ldap server.
     *
     * @param int $id LDAP database identifier
     * @param Entry $entry
     *
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function delete(int $id, Entry $entry)
    {
        return $this->getLdap($id)->delete($entry);
    }

    /**
     * Create query, execute and return its result
     *
     * @param int $id
     * @param string $base_dn
     * @param string $filter
     * @param array $options
     *
     * @return ResultInterface Query result
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function query(int $id, string $base_dn, string $filter, array $options = array()) : ResultInterface
    {
        return $this->getLdap($id)->query($base_dn, $filter, $options);
    }

    /**
     * Returns the current binding object.
     *
     * @param int $id LDAP database identifier
     *
     * @return \Korowai\Component\Ldap\Adapter\BindingInterface
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function getBinding(int $id) : BindingInterface
    {
        return $this->getLdap($id)->getBinding();
    }

    /**
     * Returns the current entry manager.
     *
     * @param int $id LDAP database identifier
     *
     * @return \Korowai\Component\Ldap\Adapter\EntryManagerInterface
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function getEntryManager(int $id) : EntryManagerInterface
    {
        return $this->getLdap($id)->getEntryManager();
    }

    /**
     * Creates a search query
     *
     * @param int $id LDAP database identifier
     * @param string $base_dn Base DN where the search will start
     * @param string $filter Filter used by ldap search
     * @param array $options Additional search options
     *
     * @return \Korowai\Component\Ldap\Adapter\EntryManagerInterface
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function createQuery(int $id, string $base_dn, string $filter, array $options = array()) : QueryInterface
    {
        return $this->getLdap($id)->createQuery($base_dn, $filter, $options);
    }
}

// vim: syntax=php sw=4 ts=4 et:
