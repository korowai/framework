<?php
/**
 * @file src/Rfc2253.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
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
    public const STRINGCHAR = '[^'.self::SPECIALCHARS.'\\\\"]';
    public const QUOTECHAR = '[^\\\\"]';

    // other productions
    public const HEXPAIR = '(?:'.self::HEXCHAR.self::HEXCHAR.')';
    public const HEXSTRING = '(?:'.self::HEXPAIR.'+)';
    public const PAIR = '(?:\\\\(?:['.self::SPECIALCHARS.'\\\\"]|'.self::HEXPAIR.'))';
    public const OID = '(?:'.self::DIGIT.'+(?:\.'.self::DIGIT.'+)*)';

    public const STRING =
        '(?:'.
            '(?:'.self::STRINGCHAR.'|'.self::PAIR.')*'.
            '|'.
            '(?:#'.self::HEXSTRING.')'.
            '|'.
            '(?:"(?:'.self::QUOTECHAR.'|'.self::PAIR.')*")'.
        ')';

    /**
     * Same as STRING but with named capture groups.
     */
    public const STRING_CAPTURE =
        '(?<string>'.
            '(?<regstring>(?:'.self::STRINGCHAR.'|'.self::PAIR.')*)'.
            '|'.
            '(?:#(?<hexstring>'.self::HEXSTRING.'))'.
            '|'.
            '(?:"(?<dqstring>(?:'.self::QUOTECHAR.'|'.self::PAIR.')*)")'.
        ')';

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
     * Matches [name](https://tools.ietf.org/html/rfc2253#section-3) (a non-empty DN).
     */
    public const NAME = '(?:'.self::NAME_COMPONENT.'(?:,'.self::NAME_COMPONENT.')*)';

    /**
     * Matches [distinguishedName](https://tools.ietf.org/html/rfc2253#section-3) (possibly empty).
     */
    public const DISTINGUISHED_NAME = '(?<dn>'.self::NAME.'?)';
}

// vim: syntax=php sw=4 ts=4 et:
