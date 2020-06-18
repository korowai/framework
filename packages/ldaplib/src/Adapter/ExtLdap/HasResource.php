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
trait HasResource
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * Returns the encapsulated resource.
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets the ResourceInterface instance to this object.
     *
     * @param resource $resource
     */
    protected function setResource($resource)
    {
        $this->resource = $resource;
    }
}

// vim: syntax=php sw=4 ts=4 et:
