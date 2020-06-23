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
 *
 * @psalm-immutable
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
     * Sets the resource to this object.
     *
     * @param resource $resource
     *
     * @return void
     */
    private function setResource($resource) : void
    {
        /** @psalm-suppress InaccessibleProperty */
        $this->resource = $resource;
    }
}

// vim: syntax=php sw=4 ts=4 et:
