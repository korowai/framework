<?php
/**
 * @file tests/Korowai/Lib/Context/ContextManagerInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ContextManagerInterfaceTrait
{
    public $enterContext = null;
    public $exitContext = null;

    public function enterContext()
    {
        return $this->enterContext;
    }

    public function exitContext(\Throwable $exception = null) : bool
    {
        return $this->exitContext;
    }
}

// vim: syntax=php sw=4 ts=4 et:
