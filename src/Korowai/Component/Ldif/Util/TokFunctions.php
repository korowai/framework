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

trait TokFunctions
{
    public static function tokMatchRe(string $re, string $src, int $offset, string &$match=null)
    {
        $status = preg_match($re, $src, $matches, 0, $offset);
        if($status) {
            $match = $matches[0];
        }
        return $status;
    }

    /**
     * Matches zero or more spaces.
     */
    public static function tokMatchFill(string $src, int $offset, string &$match=null)
    {
        return self::tokMatchRe('/\G */u', $src, $offset, $match);
    }

    /**
     * Matches CRLF or LF.
     */
    public static function tokMatchSep(string $src, int $offset, string &$match=null)
    {
        return self::tokMatchRe('/\G\r\n|\n/u', $src, $offset, $match);
    }

    /**
     * Matches a line-continuation sequence (SEP + space)
     */
    public static function tokMatchLineCont(string $src, int $offset, string &$match)
    {
        return self::tokMatchRe('/\G(?:\r\n|\n) /', $src, $offset, $match);
    }

    /**
     * Matches any "TAG" like "add:", "delete:", "changetype:" and so on.
     */
    public static function tokMatchTag(string $src, int $offset, string &$match=null)
    {
        return self::tokMatchRe('/\G\b[a-zA-Z]+:/u', $src, $offset, $match);
    }

    /**
     * Matches any possible keyword, like "add", "delete", "true", etc.
     */
    public static function tokMatchKeyword(string $src, int $offset, string &$match=null)
    {
        return self::tokMatchRe('/\G\b[a-zA-Z]+\b/u', $src, $offset, $match);
    }

    /**
     * Matches a single digit number.
     */
    public static function tokMatchSingleDigit(string $src, int $offset, string &$match=null)
    {
        return self::tokMatchRe('/\G\b\d\b/u', $src, $offset, $match);
    }

    /**
     * Matches a sequence of one or more digits.
     */
    public static function tokMatchDigits(string $src, int $offset, string &$match=null)
    {
        return self::tokMatchRe('/\G\d+\b/u', $src, $offset, $match);
    }
}

// vim: syntax=php sw=4 ts=4 et:
