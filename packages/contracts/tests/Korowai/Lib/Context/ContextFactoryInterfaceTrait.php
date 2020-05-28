<?php
/**
 * @file tests/Korowai/Lib/Context/ContextFactoryInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
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
