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

use Korowai\Lib\Context\ContextManagerInterface;

/**
 * Abstract base class for context-managed error handlers.
 *
 * The base class implements enterContext() and exitContext(). The user has to
 * implement __invoke() method as defined in ErrorHandlerInterface.
 */
abstract class AbstractManagedErrorHandler extends AbstractErrorHandler implements ContextManagerInterface
{
    use ContextManagerMethods;

    // see: https://github.com/vimeo/psalm/issues/4210

    /**
     * Actual error handler function.
     *
     * @param int    $severity level of the error raised
     * @param string $message  error message
     * @param string $file     file name the the error was raised in
     * @param int    $line     line number the error was raised at
     *
     * @return bool if it returns ``false``, then the normal error handler
     *              continues
     */
    abstract public function __invoke(int $severity, string $message, string $file, int $line): bool;
}

// vim: syntax=php sw=4 ts=4 et:
