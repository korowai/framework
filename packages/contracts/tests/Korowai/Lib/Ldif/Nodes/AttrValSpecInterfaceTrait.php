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
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AttrValSpecInterfaceTrait
{
    use NodeInterfaceTrait;

    public $attribute = null;
    public $valueSpec = null;

    public function getAttribute() : string
    {
        return $this->attribute;
    }

    public function getValueSpec() : ValueSpecInterface
    {
        return $this->valueSpec;
    }
}

// vim: syntax=php sw=4 ts=4 et:
