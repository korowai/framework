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
 * Returns a constant name for a given error code.
 *
 * @param int $err Error code
 * @return string
 */
function getPregErrConst(int $code) : string
{
    $constants = get_defined_constants(true)['pcre'];
    $errors = array_filter($constants, function($v, $k) {
        return strpos($k, "ERROR", -5) !== false && is_integer($v);
    }, ARRAY_FILTER_USE_BOTH);
    $errors = array_flip($errors);
    return $errors[$code] ?? '';
}

/**
 * Like ``preg_match()``, but throws exception instead of returning errors.
 *
 * @param string $pattern
 * @param string $subject
 * @param array $matches
 * @param int $flags
 * @param int $offset
 *
 * @return int
 *
 * @throws \Korowai\Component\Ldif\Exception\PregException
 */
function preg_match_x(
    string $pattern,
    string $subject,
    array &$matches = null,
    int $flags = 0,
    int $offset = 0
) {
    $args = func_get_args();
    if(count($args) >= 3) {
        $args[2] = &$matches;
    }

    $s = callWithErrorHandler('preg_match', $args, function($errno, $errstr) {
        throw new PregException($errstr);
    });

    if($s === false) {
        $msg = 'preg_match() failed: ' . getPregErrConst(preg_last_error());
        throw new PregException($msg);
    }

    return $s;
}

// vim: syntax=php sw=4 ts=4 et:
