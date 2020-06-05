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

    public $version = null;

    public function getVersion() : int
    {
        return $this->version;
    }
}

// vim: syntax=php sw=4 ts=4 et:
