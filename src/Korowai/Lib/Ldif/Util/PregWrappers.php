<?php
/**
 * @file src/Korowai/Lib/Ldif/Util/PregWrappers.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Util;

use Korowai\Lib\Ldif\Exception\PregException;

/**
 * Returns a constant name for a given error code.
 *
 * @param int $err Error code
 * @return string
 */
function getPregErrConst(int $code) : string
{
    $constants = get_defined_constants(true)['pcre'];
    $errors = array_filter($constants, function ($v, $k) {
        return strpos($k, "ERROR", -5) !== false && is_integer($v);
    }, ARRAY_FILTER_USE_BOTH);
    $errors = array_flip($errors);
    return $errors[$code] ?? '';
}

/**
 * An exception-throwing error handler for preg_xxx functions.
 *
 * @param int $severity
 * @param string $message
 * @param string $file
 * @param int $line
 */
function pregErrorHandler(
    int $severity,
    string $message,
    string $file,
    int $line
) {
    return exceptionErrorHandler(
        function (int $severity, string $message, string $file, int $line) {
            return new PregException($message, 0, $severity, $file, $line);
        },
        $severity,
        $message,
        $file,
        $line
    );
}

/**
 * Throws PregException() with error message based on ``preg_last_error()``.
 *
 * @param int $severity
 * @param int $file
 * @param int $line
 */
function throwLastPregError(string $func, int $severity, string $file, int $line)
{
    $message = $func . '() failed: ' . getPregErrConst(preg_last_error());
    throw new PregException($message, 0, $severity, $file, $line);
}


function callPregFunc1(string $func, array $args, int $depth = 0)
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $depth);
    $caller = end($trace);

    $file = $caller['file'];
    $line = $caller['line'];

    $retval = callWithErrorHandler(
        $func,
        $args,
        function (int $severity, string $message) use ($file, $line) {
            return pregErrorHandler($severity, $message, $file, $line);
        }
    );
    if ($retval === false) {
        throwLastPregError(ltrim($func, '\\'), E_ERROR, $file, $line);
    }
    return $retval;
}

/**
 * Like ``\preg_match()``, but throws exception instead of returning errors.
 *
 * @param string $pattern
 * @param string $subject
 * @param array $matches
 * @param int $flags
 * @param int $offset
 *
 * @return int
 *
 * @throws \Korowai\Lib\Ldif\Exception\PregException
 */
function preg_match(
    string $pattern,
    string $subject,
    array &$matches = null,
    int $flags = 0,
    int $offset = 0
) {
    $args = func_get_args();
    if (count($args) >= 3) {
        $args[2] = &$matches;
    }
    return callPregFunc1('\preg_match', $args, 1);
}

/**
 * Like ``\preg_split()``, but throws exception instead of returning errors.
 *
 * @param string $pattern
 * @param string $subject
 * @param array $matches
 * @param int $flags
 * @param int $offset
 *
 * @return int
 *
 * @throws \Korowai\Lib\Ldif\Exception\PregException
 */
function preg_split(
    string $pattern,
    string $subject,
    int $limit = -1,
    int $flags = 0
) {
    $args = func_get_args();
    return callPregFunc1('\preg_split', $args, 1);
}

// vim: syntax=php sw=4 ts=4 et:
