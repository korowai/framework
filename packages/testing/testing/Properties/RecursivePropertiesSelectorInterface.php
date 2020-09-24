<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Properties;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecursivePropertiesSelectorInterface
{
    public function selectProperties($subject) : ActualPropertiesInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
