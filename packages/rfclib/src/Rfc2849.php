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
 *
 * **Example**:
 *
 * ```
 * $result = preg_match('/\G'.Rfc2253::ATTRVAL_SPEC.'/', $subject, $matches, PREG_UNMATCHED_AS_NULL)
 * if ($result !== 0) {
 *      $attribute = $matches['attr_desc']; // attribute type with options
 *
 *      if (($url = $matches['value_url'] ?? null) !== null) {
 *          // $value = ... read from $url
 *      } elseif (($value = $matches['value_b64'] ?? null) !== null) {
 *          $value = base64_decode($value);
 *      } else {
 *          $value = $matches['value_safe'];
 *      }
 *
 *      // ...
 * }
 * ```
 */
class Rfc2849
{
    //
    // character sequences for character classes
    //

    /**
     * Same as [Rfc5234::ALPHACHARS](Rfc5234.html).
     */
    public const ALPHACHARS = Rfc5234::ALPHACHARS;

    /**
     * Same as [Rfc5234::DIGITCHARS](Rfc5234.html).
     */
    public const DIGITCHARS = Rfc5234::DIGITCHARS;

    //
    // character classes
    //

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``ALPHA = %x41-5A / %x61-7A``;
     * A-Z / a-z
     */
    public const ALPHA = Rfc5234::ALPHA;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``DIGIT = %x30-39``;
     * 0-9
     */
    public const DIGIT = Rfc5234::DIGIT;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``CR = %x0D``;
     * ASCII CR, carriage return
     */
    public const CR = Rfc5234::CR;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``LF = %x0A``;
     * ASCII LF, line feed
     */
    public const LF = Rfc5234::LF;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``SPACE = %x20``;
     * ASCII SP, space
     */
    public const SPACE = Rfc5234::SP;

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
     * ``SEP = (CR LF / LF)``
     */
    public const SEP = '(?:'.self::CR.self::LF.'|'.self::LF.')';

    //
    // productions
    //

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``FILL = *SPACE``
     */
    public const FILL = '(?:'.self::SPACE.'*)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``version-number = 1*DIGIT``
     *
     * Capture groups:
     *
     *  - *version_number*: always set.
     */
    public const VERSION_NUMBER = '(?<version_number>'.self::DIGIT.'+)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``version-spec = "version:" FILL version-number``
     */
    public const VERSION_SPEC = '(?:version:'.self::FILL.self::VERSION_NUMBER.')';

    /**
     * Like the VERSION_SPEC, but accepts missing version number.
     *
     * ``version-spec-x = "version:" FILL 0*1(version-number)``
     */
    public const VERSION_SPEC_X = '(?:version:'.self::FILL.self::VERSION_NUMBER.'?)';

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
     *
     * Capture groups:
     *
     *  - *attr_desc*: always set, contains the whole matched string.
     */
    public const ATTRIBUTE_DESCRIPTION = '(?<attr_desc>'.self::ATTRIBUTE_TYPE.'(?:;'.self::OPTIONS.')?)';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``distinguishedName = SAFE-STRING``
     *
     * Capture groups:
     *
     *  - *dn_safe*: always set, contains the whole matched string.
     */
    public const DISTINGUISHED_NAME = '(?<dn_safe>'.self::SAFE_STRING.')';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``base64-distinguishedName = SAFE-STRING``
     *
     * Capture groups:
     *
     *  - *dn_b64*: always set, contains the whole matched string.
     */
    public const BASE64_DISTINGUISHED_NAME = '(?<dn_b64>'.self::BASE64_UTF8_STRING.')';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``rdn = SAFE-STRING``;
     * a relative distinguished name defined as name-component in
     * [RFC2253](https://tools.ietf.org/html/rfc2253#section-3)
     */
    public const RDN = self::SAFE_STRING;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``base64-rdn = BASE64-UTF8-STRING``;
     * an rdn which has been base64 encoded
     */
    public const BASE64_RDN = self::BASE64_UTF8_STRING;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``dn-spec = "dn:" (FILL distinguishedName / ":" FILL base64-distinguishedName)``
     *
     * Capture groups:
     *
     *  - *dn_safe*: only set if distinguished name is specified as SAFE-STRING using single colon ``":"`` notation,
     *  - *dn_b64*: only set if distinguished name is specified as BASE64-STRING using double colon ``"::"`` notation.
     */
    public const DN_SPEC =
        '(?:'.
            'dn:(?:'.
                self::FILL.'(?:'.self::DISTINGUISHED_NAME.')'.
                '|'.
                ':'.self::FILL.'(?:'.self::BASE64_DISTINGUISHED_NAME.')'.
            ')'.
        ')';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``url = <a URL as defined in `` [RFC1738](https://tools.ietf.org/html/rfc1738) `` >``;
     * (we use URI-reference from [RFC3986](https://tools.ietf.org/html/rfc3986) instead of RFC1738)
     */
    public const URL = Rfc3986::URI_REFERENCE;

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``value-spec = ":" (FILL 0*1(SAFE-STRING) / ":" FILL (BASE64-STRING) / "<" FILL url)``
     *
     * Capture groups:
     *
     *  - *value_safe*: only set if the value-spec specifies SAFE-STRING using single colon ``":"`` notation,
     *  - *value_b64*: only set if the value-space specifies BASE64-STRING using double colon ``"::"`` notation,
     *  - *value_url*: only set if the value-spec specifies URL using colon-less-than ``":<"`` notation.
     *
     *
     * If *value_url* is present, then the capture groups of
     * [Rfc3986::URI_REFERENCE](Rfc3986.html) pattern are also present.
     */
    public const VALUE_SPEC =
        '(?:'.
            ':'.
            '(?:'.
                '(?:'. self::FILL.'(?<value_safe>'.self::SAFE_STRING.'))'.
                '|'.
                '(?::'.self::FILL.'(?<value_b64>'.self::BASE64_STRING.'))'.
                '|'.
                '(?:<'.self::FILL.'(?<value_url>'.self::URL.'))'.
            ')'.
        ')';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``control = "control:" FILL ldap-oid 0*1(1*SPACE ("true" / "false")) 0*1(value-spec) SEP``
     *
     * Capture groups:
     *
     *  - *ctl_type*: always set, contains OID of the control,
     *  - *ctl_crit*: only set if the matched string defines criticality as ``true`` or ``false``,
     *  - *ctl_value_spec*: only set if the value-spec part is present in the matched string.
     *
     *
     * If *ctl_value_spec* is present, then also the capture groups of
     * ``VALUE_SPEC`` pattern are present.
     */
    public const CONTROL =
        '(?:'.
            'control:'.self::FILL.'(?<ctl_type>'.self::LDAP_OID.')'.
            '(?:'.self::SPACE.'+(?<ctl_crit>true|false))?'.
            '(?<ctl_value_spec>'.self::VALUE_SPEC.')?'.
            self::SEP.
        ')';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``attrval-spec = AttributeDescription value-spec SEP``
     */
    public const ATTRVAL_SPEC = '(?:'.self::ATTRIBUTE_DESCRIPTION.self::VALUE_SPEC.self::SEP.')';

    /**
     * [RFC2849](https://tools.ietf.org/html/rfc2849):
     * ``ldif-attrval-record = dn-spec SEP 1*attrval-spec``
     */
    public const LDIF_ATTRVAL_RECORD = '(?:'.self::DN_SPEC.self::SEP.self::ATTRVAL_SPEC.'+)';
}

// vim: syntax=php sw=4 ts=4 et:
