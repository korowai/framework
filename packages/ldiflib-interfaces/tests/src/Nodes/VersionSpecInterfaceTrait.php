<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\NodeInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait VersionSpecInterfaceTrait
{
    use NodeInterfaceTrait;

    public $getVersion = null;

    public function getVersion() : int
    {
        return $this->getVersion;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
