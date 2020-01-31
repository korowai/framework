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
 * Data related to [RFC 2253](https://tools.ietf.org/html/rfc2253#section-3).
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
    public const _STRING = '(?:'.
                                '(?:'.self::STRINGCHAR.'|'.self::PAIR.')*'.
                                '|'.
                                '(?:#'.self::HEXSTRING.')'.
                                '|'.
                                '(?:"(?:'.self::QUOTECHAR.'|'.self::PAIR.')*")'.
                            ')';
    public const ATTRIBUTEVALUE = self::_STRING;
    public const ATTRIBUTETYPE = '(?:(?:[a-zA-Z][a-zA-Z\d-]*)|(?:\d+(?:\.\d+)*))'; // RFC2253 seems to have bug here.
    public const ATTRIBUTETYPEANDVALUE = '(?:'.self::ATTRIBUTETYPE.'='.self::ATTRIBUTEVALUE.')';
    public const NAMECOMPONENT = '(?:'.self::ATTRIBUTETYPEANDVALUE . '(?:\+'.self::ATTRIBUTETYPEANDVALUE.')*)';
    public const NAME = '(?:'.self::NAMECOMPONENT . '(?:,'.self::NAMECOMPONENT.')*)';

    /**
     * A complete regular expresions used to parse RFC2253-compliant
     * distinguished names.
     */
    public const DISTINGUISHEDNAME = '/\G'.self::NAME.'?$/';
}

// vim: syntax=php sw=4 ts=4 et:
