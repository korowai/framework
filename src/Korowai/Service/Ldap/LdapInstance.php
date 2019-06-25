<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap;

use Korowai\Component\Ldap\LdapInterface;
use Korowai\Component\Ldap\AbstractLdap;
use Korowai\Component\Ldap\Adapter\AdapterInterface;
use Korowai\Component\Ldap\Adapter\BindingInterface;
use Korowai\Component\Ldap\Adapter\EntryManagerInterface;
use Korowai\Component\Ldap\Adapter\QueryInterface;
use Korowai\Component\Ldap\Adapter\ResultInterface;
use Korowai\Component\Ldap\Entry;
use Korowai\Service\Ldap\LdapService;

class LdapInstance extends AbstractLdap
{
    /**
     * @var \Korowai\Component\Ldap\LdapInterface
     */
    protected $ldap;

    /**
     * @var string
     */
    protected $config;

    /**
     * Initializes the LdapInstance
     */
    public function __construct(LdapInterface $ldap, array $config)
    {
        $this->ldap = $ldap;
        $this->config = $config;
    }

    /**
     * Return config related to this instance.
     *
     * @param string $id
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
     * @param string $id
     * @return \Korowai\Component\Ldap\LdapInterface|null
     */
    public function getLdap() : LdapInterface
    {
        return $this->ldap;
    }

    /**
     * Returns LDAP adapter
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
     */
    public function bind(string $dn = null, string $password = null)
    {
        return $this->ldap->bind(...func_get_args());
    }

    /**
     * Unbinds the connection
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
     * @throws \Korowai\Component\Ldap\Exception\LdapException
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
     * @throws \Korowai\Component\Ldap\Exception\LdapException
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
     * @throws \Korowai\Component\Ldap\Exception\LdapException
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
     * @throws \Korowai\Component\Ldap\Exception\LdapException
     */
    public function delete(Entry $entry)
    {
        return $this->ldap->delete($entry);
    }

    /**
     * Returns the current binding object.
     *
     * @return \Korowai\Component\Ldap\Adapter\BindingInterface
     */
    public function getBinding() : BindingInterface
    {
        return $this->ldap->getBinding();
    }

    /**
     * Returns the current entry manager.
     *
     * @return \Korowai\Component\Ldap\Adapter\EntryManagerInterface
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
     * @return \Korowai\Component\Ldap\Adapter\EntryManagerInterface
     */
    public function createQuery(string $base_dn, string $filter, array $options = array()) : QueryInterface
    {
        return $this->ldap->createQuery($base_dn, $filter, $options);
    }
}

// vim: syntax=php sw=4 ts=4 et:
