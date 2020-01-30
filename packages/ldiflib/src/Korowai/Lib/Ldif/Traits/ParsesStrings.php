<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesStrings.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesStrings
{
    protected static $re_base64_char = '[\+\/0-9=A-Za-z]';
    protected static $re_safe_init_char = '[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]';
    protected static $re_safe_char = '[\x01-\x09\x0B-\x0C\x0E-\x7F]';

    abstract public function matchAheadOrThrow(
        string $pattern,
        CursorInterface $cursor,
        string $msg,
        int $flags = 0
    ) : array;
    abstract public function matchAtOrThrow(
        string $pattern,
        LocationInterface $location,
        string $msg,
        int $flags = 0
    ) : array;

    /**
     * @todo Write documentation
     *
     * @param  CursorInterface $cursor
     *
     * @throws ParserError
     */
    public function parseSafeString(CursorInterface $cursor) : string
    {
        $re = '/\G'.self::$re_safe_init_char.self::$re_safe_char.'*/';
        $matches = $this->matchAheadOrThrow($re, $cursor, "syntax error: unexpected token (expected SAFE-STRING)");
        return $matches[0][0];
    }

    /**
     * @todo Write documentation
     *
     * @param  CursorInterface $cursor
     *
     * @throws ParserError
     */
    public function parseBase64String(CursorInterface $cursor, callable $validate = null) : string
    {
        $re = '/\G'.self::$re_base64_char.'+/';
        $matches = $this->matchAtOrThrow($re, $cursor, "syntax error: unexpected token (expected BASE64-STRING)");
        $str = base64_decode($matches[0], true);
        if ($str === false) {
            throw new ParserError($cursor, "syntax error: invalid BASE64 string");
        }
        if (is_callable($validate)) {
            call_user_func($validate, $str);
        }
        $cursor->moveBy(strlen($matches[0]));

        return $matches[0];
    }

    /**
     * @todo Write documentation
     *
     * @param  CursorInterface $cursor
     *
     * @throws ParserError
     */
    public function parseBase64UtfString(CursorInterface $cursor) : string
    {
        return $this->parseBase64String($cursor, function ($string) {
            if (mb_check_encoding($string, 'utf-8') === false) {
                throw new ParserError($cursor, "syntax error: the encoded string is not a valid UTF8");
            }
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
