<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface PropertiesInterface extends \ArrayAccess
{
    public function getArrayCopy();
    public function canGetComparableFrom(PropertiesInterface $other) : bool;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
