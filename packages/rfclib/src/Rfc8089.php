<?php
/**
 * @file src/Korowai/Lib/Ldif/RFC8089.php
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
 * PCRE expressions used to parse file-URIs as defined in
 * [RFC8089](https://tools.ietf.org/html/rfc8089). The user has to add the
 * enclosing delimiters ("/").
 *
 * **Example**:
 *
 * ```
 * $result = preg_match('/^'.Rfc8089::FILE_URI.'$/', $subject, $matches, PREG_UNMATCHED_AS_NULL)
 * ```
 */
class Rfc8089 extends Rfc3986
{
    /**
     * Matches the [auth-path](https://tools.ietf.org/html/rfc8089#section-2) component of file-hier-part.
     */
    public const FILE_AUTH =
        '(?P<file_auth>'.
            '(?:(?:localhost)|'.self::HOST.')'.
        ')';

    /**
     * Matches the [local-path](https://tools.ietf.org/html/rfc8089#section-2) component of file-hier-part.
     */
    public const LOCAL_PATH = self::PATH_ABSOLUTE;

    /**
     * Matches the [auth-path](https://tools.ietf.org/html/rfc8089#section-2) component of file-hier-part.
     */
    public const AUTH_PATH =
        '(?P<auth_path>'.
            self::FILE_AUTH.'?'.self::PATH_ABSOLUTE.
        ')';

    /**
     * Matches the [file-hier-part](https://tools.ietf.org/html/rfc8089#section-2) component of file-URI.
     */
    public const FILE_HIER_PART =
        '(?P<file_hier_part>'.
            '(?:(?:\/\/'.self::AUTH_PATH.')|'.self::LOCAL_PATH.')'.
        ')';

    /**
     * Matches the [file-scheme](https://tools.ietf.org/html/rfc8089#section-2) component of file-URI.
     */
    public const FILE_SCHEME = '(?P<file_scheme>file)';

    /**
     * Matches the [file-URI](https://tools.ietf.org/html/rfc8089#section-2).
     */
    public const FILE_URI =
        '(?P<file_uri>'.
            self::FILE_SCHEME.':'.self::FILE_HIER_PART.
        ')';
}

// vim: syntax=php sw=4 ts=4 et:
