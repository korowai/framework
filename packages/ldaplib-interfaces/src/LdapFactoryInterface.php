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

/**
 * @todo Write documentation.
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapFactoryInterface
{
    /**
     * Creates and returns new instance of LdapInterface.
     *
     * @return LdapInterface
     */
    public function createLdapInterface() : LdapInterface;

    /**
     * Creates and returns new instance of BindingInterface.
     *
     * @return BindingInterface
     */
    public function createBindingInterface() : BindingInterface;

    /**
     * Creates and returns new instance of EntryManagerInterface.
     *
     * @return EntryManagerInterface
     */
    public function createEntryManagerInterface() : EntryManagerInterface;

    /**
     * Creates and returns new instance of SearchingInterface.
     *
     * @return SearchingInterface
     */
    public function createSearchingInterface() : SearchingInterface;

    /**
     * Creates and returns new instance of ComparingInterface.
     *
     * @return ComparingInterface
     */
    public function createComparingInterface() : ComparingInterface;
}

// vim: syntax=php sw=4 ts=4 et:
