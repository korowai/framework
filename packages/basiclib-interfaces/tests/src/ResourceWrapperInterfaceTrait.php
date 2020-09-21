<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Basic;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResourceWrapperInterfaceTrait
{
    public $getResource;
    public $isValid;
    public $supportsResourceType;

    public function getResource()
    {
        return $this->getResource;
    }

    public function isValid() : bool
    {
        return $this->isValid;
    }

    public function supportsResourceType(string $type) : bool
    {
        return $this->supportsResourceType;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
