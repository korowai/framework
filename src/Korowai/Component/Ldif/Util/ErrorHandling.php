<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Util;

use Korowai\Component\Ldif\Exception\PregException;

/**
 * Invokes a function with a custom error handler.
 *
 * @param callable $func The function to be called.
 * @param array $args Arguments to be passed to function.
 * @param callable $error_handler The custom error handler.
 * @param int $error_types Can be used to mask the triggering of the $handler
 */
function callWithErrorHandler(
    callable $func,
    array $args,
    ?callable $error_handler,
    int $error_types = null
) {
    set_error_handler(...(array_slice(func_get_args(), 2)));
    try {
        return call_user_func_array($func, $args);
    } finally {
        restore_error_handler();
    }
}

/**
 * Returns an error handler function which throws a predefined exception.
 *
 * The ``$exceptCtor`` must be a callable (function) which returns new
 * exception object. It must have the following interface:
 *
 * ```php
 *      function exceptCtor(string $message, int $severity, string $file, int $line) : \Exception
 * ```
 *
 * @param callable $exceptCtor A function which creates exception to be thrown.
 * @param int $severity
 * @param string $message
 * @param string $file
 * @param int $line
 *
 * @return callable
 */
function exceptionErrorHandler(
    callable $exceptCtor,
    int $severity,
    string $message,
    string $file,
    int $line
) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return false;
    }
    throw call_user_func($exceptCtor, $severity, $message, $file, $line);
}

// vim: syntax=php sw=4 ts=4 et:
