<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ContextLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * Abstract base class for custom context factories.
 */
abstract class AbstractContextFactory implements ContextFactoryInterface, ContextManagerInterface
{
    /**
     * Enter the runtime context.
     *
     * Pushes $this object onto ContextFactoryStack.
     *
     * @return $this
     */
    public function enterContext()
    {
        ContextFactoryStack::getInstance()->push($this);
        return $this;
    }

    /**
     * Exit the runtime context.
     *
     * Removes itself from the top of ContextFactoryStack.
     *
     * @param \Throwable $exception The exception thrown from user function or
     *                              ``null``.
     * @return bool ``false``
     */
    public function exitContext(?\Throwable $exception = null) : bool
    {
        ContextFactoryStack::getInstance()->pop();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
