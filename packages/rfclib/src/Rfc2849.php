<?php
/**
 * @file src/Rfc2849.php
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
 * PCRE expressions used when parsing LDIF file as defined in
 * [RFC2849](https://tools.ietf.org/html/rfc2849).
 */
class Rfc2849
{
    // character sequences for character classes
    public const ALPHACHARS = Rfc5234::ALPHACHARS;
    public const DIGITCHARS = Rfc5234::DIGITCHARS;

    // character classes
    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``ALPHA = %x41-5A / %x61-7A``;
     * A-Z / a-z
     */
    public const ALPHA = Rfc5234::ALPHA;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``attr-type-chars = ALPHA / DIGIT / "-"``
     */
    public const ATTR_TYPE_CHARS = '['.self::DIGITCHARS.self::ALPHACHARS.'-]';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``BASE64-CHAR = %x2B / %x2F / %x30-39 / %x3D / %x41-5A / %x61-7A``;
     * +, /, 0-9, =, A-Z, and a-z
     */
    public const BASE64_CHAR = '[\+\/0-9=A-Za-z]';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``opt-char = attr-type-chars``
     */
    public const OPT_CHAR = self::ATTR_TYPE_CHARS;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``SAFE-CHAR = %x01-09 / %x0B-0C / %x0E-7F``;
     * any value <= 127 decimal except NUL, LF, and CR.
     */
    public const SAFE_CHAR = '[\x01-\x09\x0B-\x0C\x0E-\x7F]';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``SAFE-INIT-CHAR = %x01-09 / %x0B-0C / %x0E-1F / %x21-39 / %x3B / %x3D-7F``;
     * any value <= 127 decimal except NUL, LF, CR, SPACE, colon (":") and less
     * than ("<").
     */
    public const SAFE_INIT_CHAR = '[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``SEP = (CR LF / LF)``; line separator LF or CRLF
     */
    public const SEP = '(?:\n|\r\n)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``BASE64-STRING = [*(BASE64-CHAR)]``
     */
    public const BASE64_STRING = '(?:'.self::BASE64_CHAR.'*)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``BASE64-UTF8-STRING = BASE64-STRING``
     */
    public const BASE64_UTF8_STRING = self::BASE64_STRING;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849)
     * ``SAFE-STRING = [SAFE-INIT-CHAR *SAFE-CHAR]``
     */
    public const SAFE_STRING = '(?:(?:'.self::SAFE_INIT_CHAR.self::SAFE_CHAR.'*)?)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``ldap-oid = 1*DIGIT *("." 1*DIGIT)``;
     * An LDAPOID, as defined in [RFC2251](https://tools.ietf.org/html/rfc2251)
     */
    public const LDAP_OID = Rfc2253::OID;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``option = 1*opt-char``;
     */
    public const OPTION = '(?:'.self::OPT_CHAR.'+)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``options = option / (option ";" options)``
     */
    public const OPTIONS = '(?:'.self::OPTION.'(?:;'.self::OPTION.')*)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``AttributeType = ldap-oid / (ALPHA *(attr-type-chars))``
     */
    public const ATTRIBUTE_TYPE = '(?:'.self::LDAP_OID.'|(?:'.self::ALPHA.self::ATTR_TYPE_CHARS.'*))';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``AttributeDescription = AttributeType [";" options]``
     */
    public const ATTRIBUTE_DESCRIPTION = '(?:'.self::ATTRIBUTE_TYPE.'(?:;'.self::OPTIONS.')?)';
}

// vim: syntax=php sw=4 ts=4 et:
