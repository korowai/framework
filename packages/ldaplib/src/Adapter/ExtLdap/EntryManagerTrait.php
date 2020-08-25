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

use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryInterface;
use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EntryManagerTrait
{
    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @return LdapLinkInterface
     *
     * @psalm-mutation-free
     */
    abstract public function getLdapLink() : LdapLinkInterface;

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_add().
     */
    public function add(EntryInterface $entry) : void
    {
        $link = $this->getLdapLink();
        with(new LdapLinkErrorHandler($link))(function () use ($link, $entry) : void {
            $link->add($entry->getDn(), $entry->getAttributes());
        });
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_modify()
     */
    public function update(EntryInterface $entry) : void
    {
        $link = $this->getLdapLink();
        with(new LdapLinkErrorHandler($link))(function () use ($link, $entry) : void {
            $link->modify($entry->getDn(), $entry->getAttributes());
        });
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_rename()
     */
    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true) : void
    {
        $link = $this->getLdapLink();
        with(new LdapLinkErrorHandler($link))(function () use ($link, $entry, $newRdn, $deleteOldRdn) : void {
            $link->rename($entry->getDn(), $newRdn, '', $deleteOldRdn);
        });
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_delete()
     */
    public function delete(EntryInterface $entry) : void
    {
        $link = $this->getLdapLink();
        with(new LdapLinkErrorHandler($link))(function () use ($link, $entry) : void {
            $link->delete($entry->getDn());
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
