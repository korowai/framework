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

namespace Korowai\Lib\Rfc;

/**
 * PCRE expressions used to parse LDIF distinguished names (DNs) as defined in
 * [RFC2253](https://tools.ietf.org/html/rfc2253). The user has to add the
 * enclosing delimiters ("/").
 *
 * **Example**:
 *
 * ```
 * $result = preg_match('/^'.Rfc2253::DISTINGUISHED_NAME.'$/', $subject, $matches, PREG_UNMATCHED_AS_NULL)
 * ```
 */
class Rfc2253
{
    // character lists for character classes
    public const ALPHACHARS = 'A-Za-z';
    public const DIGITCHARS = '0-9';
    public const HEXDIGCHARS = '0-9A-Fa-f';
    public const SPECIALCHARS = ',=+<>#;';
    public const KEYCHARCHARS = self::DIGITCHARS.self::ALPHACHARS.'-';

    // character classes
    public const ALPHA = '['.self::ALPHACHARS.']';
    public const DIGIT = '['.self::DIGITCHARS.']';
    public const HEXCHAR = '['.self::HEXDIGCHARS.']';
    public const SPECIAL = '['.self::SPECIALCHARS.']';
    public const KEYCHAR = '['.self::KEYCHARCHARS.']';

    // other productions
    public const HEXPAIR = '(?:'.self::HEXCHAR.self::HEXCHAR.')';
    public const HEXSTRING = '(?:'.self::HEXPAIR.'+)';
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
    public const OID = '(?:'.self::DIGIT.'+(?:\.'.self::DIGIT.'+)*)';

    /**
     * Matches attributeValue in the "attributeType=attributeValue" component.
     */
    public const ATTRIBUTE_VALUE = self::STRING;

    /**
     * Matches attributeType, like "ou" or "foo-bar", in the "attributeType=attributeValue".
     */
    public const ATTRIBUTE_TYPE =
        '(?:'.
            // RFC2253 has bug here (1* instead of just *), so strict RFC2253
            // does not allow one-letter attribute types such as 'O'
            '(?:'.self::ALPHA.self::KEYCHAR.'*)|'.self::OID.
        ')';

    /**
     * Matches single "attributeType=attributeValue" part of the NAME_COMPONENT.
     */
    public const ATTRIBUTE_TYPE_AND_VALUE = '(?:'.self::ATTRIBUTE_TYPE.'='.self::ATTRIBUTE_VALUE.')';

    /**
     * Matches single component of a [distinguished name](https://tools.ietf.org/html/rfc2253#section-3)
     * such as "dc=foo" or "cn=John Smith+ou=foo".
     */
    public const NAME_COMPONENT = '(?:'.self::ATTRIBUTE_TYPE_AND_VALUE.'(?:\+'.self::ATTRIBUTE_TYPE_AND_VALUE.')*)';

    /**
     * Matches non-empty [distinguished names](https://tools.ietf.org/html/rfc2253#section-3).
     */
    public const NAME =
        '(?:'.
            '(?P<first_component>'.self::NAME_COMPONENT.')'.
            '(?P<remaining_components>(?:,'.self::NAME_COMPONENT.')*)'.
        ')';

    /**
     * Matches (possibly empty) [distinguished names](https://tools.ietf.org/html/rfc2253#section-3).
     */
    public const DISTINGUISHED_NAME = '(?P<dn>'.self::NAME.'?)';
}

// vim: syntax=php sw=4 ts=4 et:
