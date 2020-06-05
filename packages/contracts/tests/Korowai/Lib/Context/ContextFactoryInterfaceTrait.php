<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ContextFactoryInterfaceTrait
{
    public $contextManager = null;

    public function getContextManager($arg) : ?ContextManagerInterface
    {
        return $this->contextManager;
    }
}

// vim: syntax=php sw=4 ts=4 et:
