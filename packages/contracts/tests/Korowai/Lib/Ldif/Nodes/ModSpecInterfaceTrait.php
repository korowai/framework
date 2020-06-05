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
trait ModSpecInterfaceTrait
{
    use NodeInterfaceTrait;
    use HasAttrValSpecsInterfaceTrait;

    public $modType = null;
    public $attribute = null;

    public function getModType() : string
    {
        return $this->modType;
    }

    public function getAttribute() : string
    {
        return $this->attribute;
    }
}

// vim: syntax=php sw=4 ts=4 et:
