<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Basic;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-immutable
 */
trait ResourceWrapperTrait
{
    /**
     * @var mixed
     *
     * @psalm-readonly
     */
    private $resource;

    /**
     * Returns whether the object supports resource of given $type.
     *
     * @psalm-mutation-free
     * @psalm-pure
     */
    abstract public function supportsResourceType(string $type): bool;

    /**
     * Returns the encapsulated resource.
     *
     * @return mixed
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
     * @psalm-mutation-free
     */
    public function isValid(): bool
    {
        return is_resource($this->resource) && ($this->supportsResourceType(get_resource_type($this->resource)));
    }
}

// vim: syntax=php sw=4 ts=4 et:
