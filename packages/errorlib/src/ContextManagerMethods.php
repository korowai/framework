<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * Context management methods for error handler classes.
 */
trait ContextManagerMethods
{
    /**
     * Sets this error handler object as error handler using PHP function
     * ``set_error_handler()`` and returns ``$this``.
     *
     * @return AbstractManagedErrorHandler
     */
    public function enterContext()
    {
        set_error_handler($this, $this->getErrorTypes());
        return $this;
    }

    /**
     * Restores original error handler using PHP function
     * \restore_error_hander() and returns ``false``.
     *
     * @return bool Always ``false``.
     */
    public function exitContext(?\Throwable $exception = null) : bool
    {
        restore_error_handler();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
