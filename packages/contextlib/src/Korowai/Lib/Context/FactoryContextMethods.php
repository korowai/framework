<?php
/**
 * @file src/Korowai/Lib/Context/FactoryContextMethods.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contextlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * Methods that any context-managed context factory should have.
 */
trait FactoryContextMethods
{
    /**
     * Pushes $this to ContextFactoryStack.
     *
     * @return $this
     */
    public function enterContext()
    {
        ContextFactoryStack::getInstance()->push($this);
        return $this;
    }

    /**
     * Pops from the top of ContextFactoryStack and returns ``false``.
     *
     * @return bool false
     */
    public function exitContext(?\Throwable $exception = null) : bool
    {
        ContextFactoryStack::getInstance()->pop();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
