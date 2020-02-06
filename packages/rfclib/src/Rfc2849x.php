<?php
/**
 * @file src/Rfc2849x.php
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
 * Extensions to
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * regular expressions.
 *
 * **Example**:
 *
 * ```
 * $result = preg_match('/\G'.Rfc2253::ATTRVAL_SPEC.'/D', $subject, $matches, PREG_UNMATCHED_AS_NULL)
 * ```
 */
class Rfc2849x extends Rfc2849
{
    /**
     * Matches [Rfc2849::SEP](Rfc2849.html) or end of string ``$``.
     */
    public const SEP_X = '(?:'. self::SEP.'|$)';

    /**
     * Matches any character except [Rfc2849x::SEP_X](Rfc2849x.html).
     */
    public const NOT_SEP_X = '(?:[^'.self::CR.self::LF.'$]|'.self::CR.'(?!'.self::LF.'))';

    /**
     * [Rfc2849::VERSION_SPEC](Rfc2849.html) with error detection.
     *
     * Matches any string that starts with ``"version:"`` and spans to the
     * nearest [Rfc::2849x::SEP_X](Rfc2849x.html). Returns ``version_error``
     * capture group if there is an error after the ``"version:"`` tag.
     *
     * *Capture groups*:
     *
     *  - ``version_number``: only set if the subject contains no syntax errors,
     *  - ``version_error``: only set if there is an error after the
     *    ``"version:"`` tag; contains the substring that failed to match.
     */
    public const VERSION_SPEC_X =
        '(?:version:'.
            self::FILL.
            '(?:'.
                '(?:'.
                    '(?:'.self::VERSION_NUMBER.'(?='.self::SEP_X.'))'.
                    '|'.
                    /*'(?:'.self::DIGIT.'*(?<version_error>(?=[^'.self::DIGITCHARS.']*)))'.*/
                    '(?:'.self::DIGIT.'*(?<version_error>'.self::NOT_SEP_X.'*)(?='.self::SEP_X.'))'.
                ')'.
            ')'.
        '?)';

    /**
     * [Rfc2849::DN_SPEC](Rfc2849.html) with error detection.
     *
     * Matches any string that starts with ``"dn:"``, and spans to the nearses
     * [Rfc::2849x::SEP_X](Rfc2849x.html). Returns one of the ``dn_*_error``
     * capture groups if there is an error after the ``"version:"`` tag.
     *
     * Capture groups:
     *
     *  - ``dn_safe``: only set if the subject contains no syntax errors and the initial tag is ``"dn:"``,
     *  - ``dn_b64``: only set if the subject contains no syntax errors and the initial tag is ``"dn::"``,
     *  - ``dn_safe_error``: only set if there is an error after the ``"dn:"``
     *    tag (single colon); contains the substring that failed to match,
     *  - ``dn_b64_error``: only set if there is an error after the ``"dn::"``
     *    tag (double colon); contains the substring that failed to match,
     */
    public const DN_SPEC_X =
        '(?:'.
            'dn:(?:'.
                '(?:(?!:)'.
                    self::FILL.
                    '(?:'.
                        '(?:'.self::DISTINGUISHED_NAME.'(?='.self::SEP_X.'))'.
                        '|'.
                        '(?:'.self::SAFE_STRING.'(?<dn_safe_error>'.self::NOT_SEP_X.'*))'.
                    ')'.
                ')'.
                '|'.
                '(?::'.
                    self::FILL.
                    '(?:'.
                        '(?:'.self::BASE64_DISTINGUISHED_NAME.'(?='.self::SEP_X.'))'.
                        '|'.
                        '(?:'.self::BASE64_UTF8_STRING.'(?<dn_b64_error>'.self::NOT_SEP_X.'*))'.
                    ')'.
                ')'.
            ')'.
        ')';

    /**
     * VALUE_SPEC with enhanced error detection. TODO:
     */
    public const VALUE_SPEC_X =
        '(?:'.
            ':'.
            '(?:'.
                '(?:(?![:<])'.
                    self::FILL.
                    '(?:'.
                        '(?:(?<value_safe>'.self::SAFE_STRING.')(?='.self::SEP_X.'))'.
                        '|'.
                        '(?:'.self::SAFE_STRING.'(?<value_safe_error>'.self::NOT_SEP_X.'*))'.
                    ')'.
                ')'.
                '|'.
                '(?::'.
                    self::FILL.
                    '(?:'.
                        '(?<value_b64>'.self::BASE64_STRING.'(?='.self::SEP_X.'))'.
                        '|'.
                        '(?:'.self::BASE64_STRING.'(?<value_b64_error>'.self::NOT_SEP_X.'*))'.
                    ')'.
                ')'.
                '|'.
                '(?:<'.
                    self::FILL.
                    '(?:'.
                        '(?<value_url>'.self::URL.'(?='.self::SEP_X.'))'.
                        '|'.
                        '(?:(?:'.self::URL.')?(?<value_url_error>'.self::NOT_SEP_X.'*))'.
                    ')'.
                ')'.
            ')'.
        ')';

    /**
     * ATTRVAL_SPEC with enhanced error detection. TODO:
     */
    public const ATTRVAL_SPEC_X = '(?:'.self::ATTRIBUTE_DESCRIPTION.self::VALUE_SPEC_X.self::SEP_X.')';


    /**
     * Defined named capture groups that appear in patterns of the Rfc2849x
     * class.
     */
    protected static $rfc2849xErrorCaptures = [
        'dn_b64_error'      => [
            'message'       => 'malformed BASE64-STRING (RFC2849)',
        ],
        'dn_safe_error'     => [
            'message'       => 'malformed SAFE-STRING (RFC2849)',
        ],
        'value_b64_error'   => [
            'message'       => 'malformed BASE64-STRING (RFC2849)',
        ],
        'value_safe_error'  => [
            'message'       => 'malformed SAFE-STRING (RFC2849)',
        ],
        'value_url_error'   => [
            'message'       => 'malformed URL (RFC2849/RFC3986)',
        ],
        'version_error'     => [
            'message'       => 'expected valid version number (RFC2849)'
        ]
    ];

    /**
     * Returns defined named capture groups that appear in patterns of the
     * Rfc2849x class.
     *
     * @return array
     */
    public static function definedErrorCaptures() : array
    {
        return self::$rfc2849xErrorCaptures;
    }

    /**
     * Returns non null *$matches*.
     *
     * @param  array $matches
     * @return array
     */
    public static function getNonNullMatches(array $matches) : array
    {
        return array_filter($matches, function ($item) {
            return is_array($item) ? $item[0] != null : $item != null;
        });
    }

    /**
     * Returns entries of *$matches* whose keys appear in
     * *definedErrorCaptures()*.
     *
     * @return array
     */
    public static function getCapturedErrors(array $matches) : array
    {
        $matches = static::getNonNullMatches($matches);
        return array_intersect_key($matches, static::definedErrorCaptures());
    }

    /**
     * Returns the entries from *definedErrorCaptures()* whose keys appear in
     * *$matches*.
     *
     * @return array
     */
    public static function getCapturedErrorsDefinitions(array $matches) : array
    {
        $matches = static::getNonNullMatches($matches);
        return array_intersect_key(static::definedErrorCaptures(), $matches);
    }

    /**
     * Returns the messages for error capture groups appearing in *$matches*.
     *
     * @return array
     */
    public static function getCapturedErrorsMessages(array $matches) : array
    {
        return array_map(function (array $def) {
            return $def['message'];
        }, static::getCapturedErrorsDefinitions($matches));
    }
}

// vim: syntax=php sw=4 ts=4 et:
