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
     * Returns whether the object supports resource of given $type
     *
     * @param  string $type
     * @return bool
     */
    abstract public function supportsResourceType(string $type) : bool;

    /**
     * @var resource
     * @psalm-readonly
     */
    private $resource;

    /**
     * Returns the encapsulated resource.
     *
     * @return resource
     *
     * @psalm-mutation-free
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Checks whether the wrapped resource is valid.
     *
     * @return bool
     */
    public function isValid() : bool
    {
        $res = $this->getResource();
        return is_resource($res) && ($this->supportsResourceType(get_resource_type($res)));
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
