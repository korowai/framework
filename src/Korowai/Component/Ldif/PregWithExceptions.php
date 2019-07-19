<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PregWithExceptions
{
    use CallWithCustomErrorHandler;

    protected static function pregErrorHandler2ZR5ZS29XXO($errno, $errstr)
    {
        if(!(error_reporting() & $errno)) {
            // This error is not included in error_reporting, so let it fall
            // through the standard PHP error handler.
            return false;
        }
        throw new \RuntimeException($errstr);
    }


    protected static function throwLastPregError()
    {
        $err = preg_last_error();
        $arr = array_flip(get_defined_constants(true)['pcre']);
        $msg = $arr[$err];
        throw new \RuntimeException($msg);
    }


    protected static function callPregFunc(callable $func, array $args)
    {
        $handler = ['self', 'pregErrorHandler2ZR5ZS29XXO'];
        return self::callWithCustomErrorHandler($handler, $func, $args);
    }


    protected static function callPregFuncReturningErrval(callable $func, array $args, $errval=false)
    {
        $retval = self::callPregFunc($func, $args);
        if($retval === $errval) {
            self::throwLastPregError();
        }
        return $retval;
    }


    public static function preg_match(string $pattern, string $subject, ?array &$matches=null, ?int $flags=0, ?int $offset=0)
    {
        $args = func_get_args();
        if(count($args) >= 3) {
            $args[2] = &$matches; // pass by reference...
        }
        return self::callPregFuncReturningErrval('preg_match', $args);
    }


    public static function preg_match_all(string $pattern, string $subject, ?array &$matches=null, ?int $flags=0, ?int $offset=0)
    {
        $args = func_get_args();
        if(count($args) >= 3) {
            $args[2] = &$matches; // pass by reference...
        }
        return self::callPregFuncReturningErrval('preg_match_all', $args);
    }


    public static function preg_replace($pattern, $replacement, $subject, ?int $limit=-1, ?int &$count=null)
    {
        $args = func_get_args();
        if(func_num_args() >= 5) {
            $args[4] = &$count; // pass by reference
        }
        return self::callPregFuncReturningErrval('preg_replace', $args, null);
    }


    public static function preg_split(string $pattern, string $subject, ?int $limit=-1, ?int $flags=0)
    {
        $args = func_get_args();
        return self::callPregFuncReturningErrval('preg_split', $args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
