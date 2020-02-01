<?php
/**
 * @file src/Korowai/Lib/Ldif/RFC2849.php
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
 * Data related to [RFC 2849](https://tools.ietf.org/html/rfc2849).
 */
class RFC2849
{
    public const SEP = '(?:\n\|\r\n)';
    public const BASE64_CHAR = '[\+\/0-9=A-Za-z]';
    public const SAFE_INIT_CHAR = '[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]';
    public const SAFE_CHAR = '[\x01-\x09\x0B-\x0C\x0E-\x7F]';
    public const BASE64_STRING = '(?:'.self::BASE64_CHAR.'*)';
    public const BASE64_UTF8_STRING = self::BASE64_STRING;
    public const SAFE_STRING = '(?:'.self::SAFE_INIT_CHAR.self::SAFE_CHAR.'*)';

    public const LDAP_OID = '(?:\d+(?:\.\d+)*)'; // +errata
    public const ALPHA = '[a-zA-Z]';
    public const ATTR_TYPE_CHARS = '[a-zA-Z\d-]';
    public const OPT_CHAR = self::ATTR_TYPE_CHARS;
    public const OPTION = '(?:'.self::OPT_CHAR.'+)';
    public const OPTIONS = '(?:'.self::OPTION.'(?:;'.self::OPTION.')*)';
    public const ATTRIBUTE_TYPE = '(?:'.self::LDAP_OID.'|(?:'.self::ALPHA.self::ATTR_TYPE_CHARS.'*))';
    public const ATTRIBUTE_DESCRIPTION = '(?:'.self::ATTRIBUTE_TYPE.'(?:;'.self::OPTIONS.')?)';
}

// vim: syntax=php sw=4 ts=4 et:
