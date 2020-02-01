<?php
/**
 * @file src/Korowai/Lib/Ldif/RFC1738.php
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
 * Data related to [RFC 1738](https://tools.ietf.org/html/rfc1738).
 */
class RFC1738
{
    // character lists for character classes
    public const HEXCHARS = 'a-fA-F0-9';
    public const RESERVEDCHARS = ';\/\?:@&=';
    public const SAFECHARS = '\$_.+-';
    public const EXTRACHARS = '!\*\'\(\),';
    public const NATIONALCHARS = '\{\}\|\\\\\^~\[\]`';
    public const LOALPHACHARS = 'a-z';
    public const HIALPHACHARS = 'A-Z';
    public const DIGITCHARS = '0-9';
    public const ALPHACHARS = self::LOALPHACHARS.self::HIALPHACHARS;
    public const ALPHADIGITCHARS = self::ALPHACHARS.self::DIGITCHARS;
    public const UNRESERVEDCHARS = self::ALPHACHARS.self::DIGITCHARS.self::EXTRACHARS.self::SAFECHARS;

    // charcter classes
    public const HEX = '['.self::HEXCHARS.']';
    public const RESERVED = '['.self::RESERVEDCHARS.']';
    public const SAFE = '['.self::SAFECHARS.']';
    public const EXTRA = '['.self::EXTRACHARS.']';
    public const NATIONAL = '['.self::NATIONALCHARS.']';
    public const ALPHA = '['.self::ALPHACHARS.']';
    public const ALPHADIGIT = '['.self::ALPHADIGITCHARS.']';
    public const UNRESERVED = '['.self::UNRESERVEDCHARS.']';
    public const RESERVEDUNRESERVED = '['.self::RESERVEDCHARS.self::UNRESERVEDCHARS.']';

    // sequences
    public const ESCAPE = '(?:%'.self::HEX.self::HEX.')';
    public const UCHAR = '(?:'.self::UNRESERVED.'|'.self::ESCAPE.')';
    public const XCHAR = '(?:'.self::RESERVEDUNRESERVED.'|'.self::ESCAPE.')';
    public const PASSWORD = '(?:(?:'.self::UCHAR.'|[;\?&=])*)';
    public const USER = '(?:(?:'.self::UCHAR.'|[;\?&=])*)';
    public const URLPATH = '(?:'.self::XCHAR.'*)';
    public const PORT = '(?:\d+)';
    public const HOSTNUMBER = '(?:\d+\.\d+\.\d+\.\d+)';
    public const TOPLABEL =
        '(?:'.
            self::ALPHA.
            '|'.
            '(?:'.self::ALPHA.'['.self::ALPHADIGITCHARS.'-]*'.self::ALPHADIGIT.')'.
        ')';
    public const DOMAINLABEL =
        '(?:'.
            self::ALPHADIGIT.
            '|'.
            '(?:'.self::ALPHADIGIT.'['.self::ALPHADIGITCHARS.'-]*'.self::ALPHADIGIT.')'.
        ')';
    public const HOSTNAME = '(?:(?:'.self::DOMAINLABEL.'\.)*'.self::TOPLABEL.')';
    public const HOST = '(?:'.self::HOSTNAME.'|'.self::HOSTNUMBER.')';
    public const HOSTPORT = '(?:'.self::HOST.'(?::'.self::PORT.')?)';
    public const LOGIN = '(?:(?:'.self::USER.'(?::'.self::PASSWORD.')?@)?'.self::HOSTPORT.')';
    public const IP_SCHEMEPART = '(?:\/\/'.self::LOGIN.'(?:\/'.self::URLPATH.')?)';
    public const SCHEMEPART = '(?:'.self::XCHAR.'*|'.self::IP_SCHEMEPART.')';
    public const SCHEME = '(?:['.self::LOALPHACHARS.'\d\+\.-]+)';

    // GENERIC
    public const GENERICURL = '(?:'.self::SCHEME.':'.self::SCHEMEPART.')';

    // FILE
    public const FSEGMENT = '(?:(?:'.self::UCHAR.'|[\?:@&=])*)';
    public const FPATH = '(?:'.self::FSEGMENT.'(?:\/'.self::FSEGMENT.')*)';
    public const FILEURL = '(?:file:\/\/(?:'.self::HOST.'|localhost)?\/'.self::FPATH.')';
}

// vim: syntax=php sw=4 ts=4 et:
