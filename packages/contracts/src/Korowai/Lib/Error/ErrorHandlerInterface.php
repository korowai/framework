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
 * An interface for error handler objects.
 */
interface ErrorHandlerInterface
{
    /**
     * Actual error handler function.
     *
     * @param  int $severity Level of the error raised.
     * @param  string $message Error message.
     * @param  string $file File name the the error was raised in.
     * @param  int $line Line number the error was raised at.
     *
     *
     * @return bool If it returns ``false``, then the normal error handler
     *              continues.
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool;

    /**
     * Returns an integer used to mask the triggering of the error handler.
     *
     * When the handler is activated it usually calls ``set_error_handler($this, $this->getErrorTypes())``
     *
     * @return int
     */
    public function getErrorTypes() : int;
}

// vim: syntax=php sw=4 ts=4 et:
