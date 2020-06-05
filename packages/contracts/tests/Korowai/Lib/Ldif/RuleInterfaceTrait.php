<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\ParserStateInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait RuleInterfaceTrait
{
    public function repeat(ParserStateInterface $state, array &$values = null, int $min = 0, int $max = null) : bool
    {
        return false;
    }

    public function parse(ParserStateInterface $state, &$value = null, bool $trying = false) : bool
    {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
