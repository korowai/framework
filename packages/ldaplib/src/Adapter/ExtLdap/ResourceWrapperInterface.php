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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResourceWrapperInterface
{
    /**
     * Returns the encapsulated resource.
     *
     * @return resource
     */
    public function getResource();

    /**
     * Checks whether the Result represents a valid resource of particular
     * type.
     *
     * @return bool
     */
    public function isValid() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
