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
     * Checks whether the wrapped resource is valid.
     *
     * @return bool
     */
    public function isValid() : bool;

    /**
     * Returns whether the object supports resource of given $type
     *
     * @param  string $type
     * @return bool
     */
    public function supportsResourceType(string $type) : bool;
}

// vim: syntax=php sw=4 ts=4 et:
