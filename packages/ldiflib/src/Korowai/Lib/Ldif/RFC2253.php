<?php
/**
 * @file src/Korowai/Lib/Ldif/RFC2253.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Data related to [RFC 2253](https://tools.ietf.org/html/rfc2253).
 */
class RFC2253
{
    public const HEXCHAR = '[0-9a-fA-F]';
    public const HEXPAIR = '(?:'.self::HEXCHAR.self::HEXCHAR.')';
    public const HEXSTRING = '(?:'.self::HEXPAIR.'+)';
    public const SPECIALCHARS = ',=+<>#;';
    public const PAIR = '(?:\\\\(?:['.self::SPECIALCHARS.'\\\\"]|'.self::HEXPAIR.'))';
    public const STRINGCHAR = '[^'.self::SPECIALCHARS.'\\\\"]';
    public const QUOTECHAR = '[^\\\\"]';
    public const STRING = '(?:'.
                                '(?:'.self::STRINGCHAR.'|'.self::PAIR.')*'.
                                '|'.
                                '(?:#'.self::HEXSTRING.')'.
                                '|'.
                                '(?:"(?:'.self::QUOTECHAR.'|'.self::PAIR.')*")'.
                          ')';
    public const ATTRIBUTE_VALUE = self::STRING;
    public const ATTRIBUTE_TYPE = '(?:(?:[a-zA-Z][a-zA-Z\d-]*)|(?:\d+(?:\.\d+)*))'; // RFC2253 seems to have bug here.
    public const ATTRIBUTE_TYPE_AND_VALUE = '(?:'.self::ATTRIBUTE_TYPE.'='.self::ATTRIBUTE_VALUE.')';
    public const NAME_COMPONENT = '(?:'.self::ATTRIBUTE_TYPE_AND_VALUE . '(?:\+'.self::ATTRIBUTE_TYPE_AND_VALUE.')*)';
    public const NAME = '(?:'.self::NAME_COMPONENT . '(?:,'.self::NAME_COMPONENT.')*)';

    /**
     * Regular expresion matching RFC2253-compliant distinguished names.
     */
    public const DISTINGUISHED_NAME = '(?:'.self::NAME.'?)';
}

// vim: syntax=php sw=4 ts=4 et:
