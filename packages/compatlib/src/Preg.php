<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Compat;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\callerErrorHandler;

/**
 * Contains methods that help adapting PCRE functions.
 */
final class Preg
{
    public static $pregErrorMessages = [
        PREG_NO_ERROR => 'No error',
        PREG_INTERNAL_ERROR => 'Internal error',
        PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit exhaused',
        PREG_RECURSION_LIMIT_ERROR => 'Recursion limit exhaused',
        PREG_BAD_UTF8_ERROR => 'Malformed utf-8 data',
        PREG_BAD_UTF8_OFFSET_ERROR => 'Offset does not correspond to the begin of a valid utf-8 code',
        PREG_JIT_STACKLIMIT_ERROR => 'Failed due to limited JIT stack space',
    ];

    /**
     * Returns an array of PCRE constatns.
     */
    public static function getPcreConstants(): array
    {
        /** @var array[] */
        $constants = get_defined_constants(true);

        return $constants['pcre'] ?? [];
    }

    /**
     * Returns constant's name for a given PCRE error code (PREG_XXX_ERROR) or
     * a string in form "Error $code" if there is no PCRE constatnt for given
     * $code.
     */
    public static function getPregErrorConst(int $code): string
    {
        $constants = self::getPcreConstants();
        $errors = array_filter($constants, function ($val, $key) {
            return false !== strpos($key, 'ERROR', -5) && is_integer($val);
        }, ARRAY_FILTER_USE_BOTH);
        $errors = array_flip($errors);

        return $errors[$code] ?? 'Error '.(string) $code;
    }

    /**
     * Returns an error message for given error *$code* returned from preg_last_error().
     *
     * @param mixed $func
     */
    public static function getPregErrorMessage(int $code, $func = null): string
    {
        $prefix = is_string($func) ? ltrim($func, '\\').'(): ' : '';

        return $prefix.(static::$pregErrorMessages[$code] ?? self::getPregErrorConst($code));
    }

    /**
     * Invokes *$func* and throws PregExceotion if error is detected (preg_last_error())..
     *
     * @throws PregException
     *
     * @return mixed
     */
    public static function callPregFunc(callable $func, array $args, int $distance = 0)
    {
        // PCRE invokes PHP error handler (see set_error_handler()) before the
        // PCRE last error code (preg_last_error()) is set. So, we can't just
        // throw exception from the error handler ($callback below), because
        // the error code is unavailable at this stage. The $callback is used
        // mainly to capture $severity, $message, $file and $line. The precise
        // error message, on the other hand, is only available in the error
        // handler ($callback).
        $callback = function (int $s, string $m, string $f, int $l) use (&$severity, &$message, &$file, &$line) {
            [$severity, $message, $file, $line] = [$s, $m, $f, $l];

            return true; // handled!
        };
        $handler = callerErrorHandler($callback, 1 + $distance);
        $retval = with($handler)(function ($eh) use ($func, $args) {
            return call_user_func_array($func, $args);
        });
        if (PREG_NO_ERROR !== ($error = preg_last_error())) {
            if (!isset($severity)) {
                // PCRE didn't invoke error handler, but there was an error anyway.
                $severity = E_ERROR;
                $message = self::getPregErrorMessage($error, $func);
                $file = $handler->getCallerFile();
                $line = $handler->getCallerLine();
            }

            throw new PregException($message, $error, $severity, $file, $line);
        }

        return $retval;
    }
}

// vim: syntax=php sw=4 ts=4 et:
