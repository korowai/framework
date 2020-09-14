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
 */
interface ResourceWrapperInterface
{
    /**
     * Returns the encapsulated resource.
     *
     * @return mixed
     *
     * @psalm-mutation-free
     */
    public function getResource();

    /**
     * Checks whether the wrapped resource is valid.
     *
     * @return bool
     *
     * @psalm-mutation-free
     */
    public function isValid() : bool;

    /**
     * Returns whether the object supports resource of given $type
     *
     * @param  string $type
     * @return bool
     *
     * @psalm-mutation-free
     * @psalm-pure
     */
    public function supportsResourceType(string $type) : bool;
}

// vim: syntax=php sw=4 ts=4 et tw=120:
