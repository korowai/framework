<?php
/**
 * @file src/Korowai/Service/Ldap/LdapInstance.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap;

use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\AbstractLdap;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Entry;
use Korowai\Service\Ldap\LdapService;

class LdapInstance extends AbstractLdap
{
    /**
     * @var \Korowai\Lib\Ldap\LdapInterface
     */
    protected $ldap;

    /**
     * @var string
     */
    protected $config;

    /**
     * Initializes the LdapInstance
     *
     * @param LdapInterface $ldap
     * @param array $config
     */
    public function __construct(LdapInterface $ldap, array $config)
    {
        $this->ldap = $ldap;
        $this->config = $config;
    }

    /**
     * Return config related to this instance.
     *
     * @return array|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get ID of this instance.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->config['id'];
    }

    /**
     * Get Ldap interface
     *
     * @return \Korowai\Lib\Ldap\LdapInterface|null
     */
    public function getLdap() : LdapInterface
    {
        return $this->ldap;
    }

    /**
     * Returns LDAP adapter
     *
     * @return AdapterInterface Adapter
     */
    public function getAdapter() : AdapterInterface
    {
        return $this->ldap->getAdapter();
    }

    /**
     * Check whether the connection was already bound or not.
     *
     * @return bool
     */
    public function isBound() : bool
    {
        return $this->ldap->isBound();
    }

    /**
     * Binds the connection against a DN and password
     *
     * @param string $dn        The user's DN
     * @param string $password  The associated password
     *
     * @return bool
     */
    public function bind(string $dn = null, string $password = null)
    {
        return $this->ldap->bind(...func_get_args());
    }

    /**
     * Unbinds the connection
     *
     * @return bool
     */
    public function unbind()
    {
        return $this->ldap->unbind();
    }

    /**
     * Adds a new entry in the LDAP server.
     *
     * @param Entry $entry
     *
     * @throws \Korowai\Lib\Ldap\Exception\LdapException
     */
    public function add(Entry $entry)
    {
        return $this->ldap->add($entry);
    }

    /**
     * Updates entry in Ldap server.
     *
     * @param Entry $entry
     * @param string $newRdn
     * @param bool $deleteOldRdn
     *
     * @throws \Korowai\Lib\Ldap\Exception\LdapException
     */
    public function rename(Entry $entry, string $newRdn, bool $deleteOldRdn = true)
    {
        return $this->ldap->rename($entry, $newRdn, $deleteOldRdn);
    }

    /**
     * Renames an entry on the LDAP server.
     *
     * @param Entry $entry
     *
     * @throws \Korowai\Lib\Ldap\Exception\LdapException
     */
    public function update(Entry $entry)
    {
        return $this->ldap->update($entry);
    }

    /**
     * Removes entry from the Ldap server.
     *
     * @param Entry $entry
     *
     * @throws \Korowai\Lib\Ldap\Exception\LdapException
     */
    public function delete(Entry $entry)
    {
        return $this->ldap->delete($entry);
    }

    /**
     * Returns the current binding object.
     *
     * @return \Korowai\Lib\Ldap\Adapter\BindingInterface
     */
    public function getBinding() : BindingInterface
    {
        return $this->ldap->getBinding();
    }

    /**
     * Returns the current entry manager.
     *
     * @return \Korowai\Lib\Ldap\Adapter\EntryManagerInterface
     */
    public function getEntryManager() : EntryManagerInterface
    {
        return $this->ldap->getEntryManager();
    }

    /**
     * Creates a search query
     *
     * @param string $base_dn Base DN where the search will start
     * @param string $filter Filter used by ldap search
     * @param array $options Additional search options
     *
     * @return \Korowai\Lib\Ldap\Adapter\EntryManagerInterface
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = array()) : SearchQueryInterface
    {
        return $this->ldap->createSearchQuery($base_dn, $filter, $options);
    }
}

// vim: syntax=php sw=4 ts=4 et:
