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

use Korowai\Lib\Ldif\InputInterface;
use Korowai\Tests\Lib\Ldif\InputInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PreprocessorInterfaceTrait
{
    public function preprocess(string $source, string $filename = null) : InputInterface
    {
        return new class implements InputInterface {
            use InputInterfaceTrait;
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
