<?php
/**
 * @file src/Rfc3986.php
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
 * PCRE expressions used to parse URIs as defined in
 * [RFC3986](https://tools.ietf.org/html/rfc3986).
 *
 * **Example**:
 *
 * ```
 * $result = preg_match('/^'.Rfc3986::URI_REFERENCE.'$/', $subject, $matches, PREG_UNMATCHED_AS_NULL)
 * ```
 */
class Rfc3986 extends AbstractRuleSet
{
    // character lists for character classes
    public const ALPHACHARS = Rfc5234::ALPHACHARS;
    public const DIGITCHARS = Rfc5234::DIGITCHARS;
    public const HEXDIGCHARS = Rfc5234::HEXDIGCHARS.'a-f';
    public const GEN_DELIM_CHARS = ':\/\?#\[\]@';
    public const SUB_DELIM_CHARS = '!\$&\'\(\)\*\+,;=';
    public const RESERVEDCHARS = self::GEN_DELIM_CHARS.self::SUB_DELIM_CHARS;
    public const UNRESERVEDCHARS = self::ALPHACHARS.self::DIGITCHARS.'\._~-';
    public const PCHARCHARS = ':@'.self::SUB_DELIM_CHARS.self::UNRESERVEDCHARS;

    // character classes
    public const ALPHA = Rfc5234::ALPHA;
    public const DIGIT = Rfc5234::DIGIT;
    public const HEXDIG = '['.self::HEXDIGCHARS.']';
    public const SUB_DELIMS = '['.self::SUB_DELIM_CHARS.']';
    public const GEN_DELIMS = '['.self::GEN_DELIM_CHARS.']';
    public const RESERVED = '['.self::RESERVEDCHARS.']';
    public const UNRESERVED = '['.self::UNRESERVEDCHARS.']';

    // (sub)expressions
    public const PCT_ENCODED = '(?:%'.self::HEXDIG.self::HEXDIG.')';
    public const PCHAR = '(?:['.self::PCHARCHARS.']|'.self::PCT_ENCODED.')';
    public const SEGMENT_NZ_NC = '(?:(?:[@'.self::SUB_DELIM_CHARS.self::UNRESERVEDCHARS.']|'.self::PCT_ENCODED.')+)';
    public const SEGMENT_NZ = '(?:'.self::PCHAR.'+)';
    public const SEGMENT = '(?:'.self::PCHAR.'*)';
    public const PATH_EMPTY = '(?<path_empty>)';
    public const PATH_NOSCHEME = '(?<path_noscheme>'.self::SEGMENT_NZ_NC.'(?:\/'.self::SEGMENT.')*)';
    public const PATH_ROOTLESS = '(?<path_rootless>'.self::SEGMENT_NZ.'(?:\/'.self::SEGMENT.')*)';
    public const PATH_ABSOLUTE = '(?<path_absolute>\/(?:'.self::SEGMENT_NZ.'(?:\/'.self::SEGMENT.')*)?)';
    public const PATH_ABEMPTY = '(?<path_abempty>(?:\/'.self::SEGMENT.')*)';
    public const REG_NAME =
        '(?<reg_name>'.
            '(?:['.self::SUB_DELIM_CHARS.self::UNRESERVEDCHARS.']|'.self::PCT_ENCODED.')*'.
        ')';
    public const DEC_OCTET =
        '(?:'.
            self::DIGIT.                    // 0-9
            '|'.
            '[1-9]'.self::DIGIT.            // 10-99
            '|'.
            '1'.self::DIGIT.self::DIGIT.    // 100-199
            '|'.
            '2[0-4]'.self::DIGIT.           // 200-249
            '|'.
            '25[0-5]'.                      // 250-255
        ')';
    public const DEC4OCTETS =
        '(?:'.
                 self::DEC_OCTET.
            '\.'.self::DEC_OCTET.
            '\.'.self::DEC_OCTET.
            '\.'.self::DEC_OCTET.
        ')';
    public const IPV4ADDRESS = '(?<ipv4address>'.self::DEC4OCTETS.')';
    public const IPV6V4ADDRESS = '(?<ipv6v4address>'.self::DEC4OCTETS.')';
    public const H16 = '(?:'.self::HEXDIG.'{1,4})';
    public const LS32 = '(?<ls32>(?:'.self::H16.':'.self::H16.')|'.self::IPV6V4ADDRESS.')';
    public const IPV6ADDRESS =
        '(?<ipv6address>(?|'.
             '(?:'.                                                    '(?:'.self::H16.':){6,6}'.self::LS32.')'.
            '|(?:'.                                                  '::(?:'.self::H16.':){5,5}'.self::LS32.')'.
            '|(?:'.                           '(?:'.self::H16.')?'.  '::(?:'.self::H16.':){4,4}'.self::LS32.')'.
            '|(?:'.    '(?:(?:'.self::H16.':){0,1}'.self::H16.')?'.  '::(?:'.self::H16.':){3,3}'.self::LS32.')'.
            '|(?:'.    '(?:(?:'.self::H16.':){0,2}'.self::H16.')?'.  '::(?:'.self::H16.':){2,2}'.self::LS32.')'.
            '|(?:'.    '(?:(?:'.self::H16.':){0,3}'.self::H16.')?'.  '::(?:'.self::H16.':){1,1}'.self::LS32.')'.
            '|(?:'.    '(?:(?:'.self::H16.':){0,4}'.self::H16.')?'.  '::'.self::LS32.')'.
            '|(?:'.    '(?:(?:'.self::H16.':){0,5}'.self::H16.')?'.  '::'.self::H16.')'.
            '|(?:'.    '(?:(?:'.self::H16.':){0,6}'.self::H16.')?'.  '::)'.
        '))';
    public const IPVFUTURE =
        '(?<ipvfuture>'.
            'v'.self::HEXDIG.'+'.
            '\.[:'.self::SUB_DELIM_CHARS.self::UNRESERVEDCHARS.']+'.
        ')';
    public const IP_LITERAL =
        '(?<ip_literal>'.
            '\['.self::IPV6ADDRESS.'\]'.
            '|'.
            '\['.self::IPVFUTURE.'\]'.
        ')';

    /**
     * Matches the [port](https://tools.ietf.org/html/rfc3986#section-3.2.3) component of authority.
     */
    public const PORT =
        '(?<port>'.
            self::DIGIT.'*'.
        ')';

    /**
     * Matches the [host](https://tools.ietf.org/html/rfc3986#section-3.2.2) component of authority.
     */
    public const HOST =
        '(?<host>'.
            self::IP_LITERAL.
            '|'.
            self::IPV4ADDRESS.
            '|'.
            self::REG_NAME.
        ')';

    /**
     * Matches the [user information](https://tools.ietf.org/html/rfc3986#section-3.2.1) component of authority.
     */
    public const USERINFO =
        '(?<userinfo>'.
            '(?:[:'.self::SUB_DELIM_CHARS.self::UNRESERVEDCHARS.']|'.self::PCT_ENCODED.')*'.
        ')';

    /**
     * Matches the [authority](https://tools.ietf.org/html/rfc3986#section-3.2) component of URI.
     */
    public const AUTHORITY =
        '(?<authority>'.
            '(?:'.self::USERINFO.'@)?'.
            self::HOST.
            '(?::'.self::PORT.')?'.
        ')';

    /**
     * Matches the [scheme](https://tools.ietf.org/html/rfc3986#section-3.1) component of URI.
     */
    public const SCHEME =
        '(?<scheme>'.
            self::ALPHA.'['.self::ALPHACHARS.self::DIGITCHARS.'\+\.-]*'.
        ')';

    /**
     * Matches the [relative-part](https://tools.ietf.org/html/rfc3986#section-4.2) component of relative-ref.
     */
    public const RELATIVE_PART =
        '(?<relative_part>'.
            '(?:\/\/'.self::AUTHORITY.self::PATH_ABEMPTY.')'.
            '|'.
            self::PATH_ABSOLUTE.
            '|'.
            self::PATH_NOSCHEME.
            //'|'.
            //self::PATH_ABEMPTY. // +errata [5428] (rejected)
            '|'.
            self::PATH_EMPTY.
        ')';

    /**
     * Matches the [hier-part](https://tools.ietf.org/html/rfc3986#section-3) component of URI.
     */
    public const HIER_PART =
        '(?<hier_part>'.
            '(?:\/\/'.self::AUTHORITY.self::PATH_ABEMPTY.')'.
            '|'.
            self::PATH_ABSOLUTE.
            '|'.
            self::PATH_ROOTLESS.
            '|'.
            self::PATH_EMPTY.
        ')';

    /**
     * Matches the [fragment](https://tools.ietf.org/html/rfc3986#section-3.5) component of URI.
     */
    public const FRAGMENT = '(?<fragment>(?:'.self::PCHAR.'|\/|\?)*)';

    /**
     * Matches the [query](https://tools.ietf.org/html/rfc3986#section-3.4) component of URI.
     */
    public const QUERY = '(?<query>(?:'.self::PCHAR.'|\/|\?)*)';

    /**
     * Matches [relative-ref](https://tools.ietf.org/html/rfc3986#section-4.2).
     */
    public const RELATIVE_REF =
        '(?<relative_ref>'.
            self::RELATIVE_PART.
            '(?:\?'.self::QUERY.')?'.
            '(?:#'.self::FRAGMENT.')?'.
        ')';

    /**
     * Matches [absolute-URI](https://tools.ietf.org/html/rfc3986#section-4.3).
     */
    public const ABSOLUTE_URI =
        '(?<absolute_uri>'.
            self::SCHEME.
            ':'.
            self::HIER_PART.
            '(?:\?'.self::QUERY.')?'.
        ')';

    /**
     * Matches [URI](https://tools.ietf.org/html/rfc3986#section-3).
     */
    public const URI =
        '(?<uri>'.
            self::SCHEME.
            ':'.
            self::HIER_PART.
            '(?:\?'.self::QUERY.')?'.
            '(?:#'.self::FRAGMENT.')?'.
        ')';

    /**
     * Matches [URI-reference](https://tools.ietf.org/html/rfc3986#section-4.1).
     */
    public const URI_REFERENCE =
        '(?J)(?<uri_reference>'.
            self::URI.
            '|'.
            self::RELATIVE_REF.
        ')';

    protected static $rfc3986Rules = [
        'ALPHACHARS',
        'DIGITCHARS',
        'HEXDIGCHARS',
        'GEN_DELIM_CHARS',
        'SUB_DELIM_CHARS',
        'RESERVEDCHARS',
        'UNRESERVEDCHARS',
        'PCHARCHARS',
        'ALPHA',
        'DIGIT',
        'HEXDIG',
        'SUB_DELIMS',
        'GEN_DELIMS',
        'RESERVED',
        'UNRESERVED',
        'PCT_ENCODED',
        'PCHAR',
        'SEGMENT_NZ_NC',
        'SEGMENT_NZ',
        'SEGMENT',
        'PATH_EMPTY',
        'PATH_NOSCHEME',
        'PATH_ROOTLESS',
        'PATH_ABSOLUTE',
        'PATH_ABEMPTY',
        'REG_NAME',
        'DEC_OCTET',
        'DEC4OCTETS',
        'IPV4ADDRESS',
        'IPV6V4ADDRESS',
        'H16',
        'LS32',
        'IPV6ADDRESS',
        'IPVFUTURE',
        'IP_LITERAL',
        'PORT',
        'HOST',
        'USERINFO',
        'AUTHORITY',
        'SCHEME',
        'RELATIVE_PART',
        'HIER_PART',
        'FRAGMENT',
        'QUERY',
        'RELATIVE_REF',
        'ABSOLUTE_URI',
        'URI',
        'URI_REFERENCE',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getClassRuleNames() : array
    {
        return self::$rfc3986Rules;
    }
}

// vim: syntax=php sw=4 ts=4 et:
