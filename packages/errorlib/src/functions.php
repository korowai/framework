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
 * A shortcut to EmptyErrorHandler::getInstance().
 *
 * @return EmptyErrorHandler
 */
function emptyErrorHandler() : EmptyErrorHandler
{
    return EmptyErrorHandler::getInstance();
}

/**
* A shortcut to new ErrorHandler(...).
*
* @param  callable $errorHandler User-provided error handler function.
* @param  int $errorTypes Can be used to mask the triggering of the error
*                        handler function.
*
* @return ErrorHandler
*/
function errorHandler(callable $errorHandler, int $errorTypes = E_ALL | E_STRICT) : ErrorHandler
{
    return new ErrorHandler($errorHandler, $errorTypes);
}

/**
 * Creates and returns new ExceptionErrorHandler.
 *
 * If ``$arg`` is a callable it should have the prototype
 *
 * ```php
 * function f(int $severity, string $message, string $file, int $line)
 * ```
 *
 * and it should return new exception object.
 *
 * If it is a class name, the class should provide constructor
 * having interface compatible with PHP's \ErrorException class.
 *
 * @param  mixed $arg Either a callable or an exception's class name.
 * @param  int $errorTypes Error types handled by the new handler.
 *
 * @return ExceptionErrorHandler
 */
function exceptionErrorHandler($arg = null, int $errorTypes = E_ALL | E_STRICT) : ExceptionErrorHandler
{
    $exceptionGenerator = ExceptionErrorHandler::makeExceptionGenerator($arg);
    return new ExceptionErrorHandler($exceptionGenerator, $errorTypes);
}

/**
 * A shortcut to new CallerErrorHandler(...).
 *
 * @param  callable $errorHandler
 * @param  int $distance
 * @param  int $errorTypes
 *
 * @return CallerErrorHandler
 */
function callerErrorHandler(
    callable $errorHandler,
    int $distance = 1,
    int $errorTypes = E_ALL | E_STRICT
) : CallerErrorHandler {
    return new CallerErrorHandler($errorHandler, 1 + $distance, $errorTypes);
}

/**
 * Creates and returns new CallerExceptionErrorHandler.
 *
 * If ``$arg`` is a callable it should have the prototype
 *
 * ```php
 * function f(int $severity, string $message, string $file, int $line)
 * ```
 *
 * and it should return new exception object.
 *
 * If it is a class name, the class should provide constructor
 * having interface compatible with PHP's \ErrorException class.
 *
 * @param  mixed $arg Either a callable or an exception's class name.
 * @param  mixed $distance The distance from our caller to his caller.
 * @param  int $errorTypes Error types handled by the new handler.
 *
 * @return CallerExceptionErrorHandler
 */
function callerExceptionErrorHandler(
    $arg = null,
    int $distance = 1,
    int $errorTypes = E_ALL | E_STRICT
) : CallerExceptionErrorHandler {
    $exceptionGenerator = ExceptionErrorHandler::makeExceptionGenerator($arg);
    return new CallerExceptionErrorHandler($exceptionGenerator, 1 + $distance, $errorTypes);
}

// vim: syntax=php sw=4 ts=4 et:
