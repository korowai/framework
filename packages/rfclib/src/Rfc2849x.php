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
     * [Rfc2849::VERSION_SPEC](Rfc2849.html) with enhanced error detection. The
     * pattern matches any string starting with ``"version:"`` tag until (but
     * excluding) next [Rfc::2849x::SEP_X](Rfc2849x.html). The pattern sets
     * ``"version_error"`` named capture group if there is an error after the
     * ``"version:"`` tag.
     *
     * Capture groups:
     *
     *  - *version_number*: only set if the subject contains no syntax errors,
     *  - *version_error*: only set if there is an error after the
     *    ``"version:"`` tag. The captured group contains the failed substring.
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
     * DN_SPEC with enhanced error detection. The pattern matches any string
     * starting with ``"dn:"`` tag, but it sets ``"error"`` named capture group
     * if there is an error after the ``"version:"`` tag.
     *
     * Capture groups:
     *
     *  - *dn_safe*: only set if the subject contains no syntax errors and the initial tag is ``"dn:"``,
     *  - *dn_b64*: only set if the subject contains no syntax errors and the initial tag is ``"dn::"``,
     *  - *dn_safe_error*: only set if there is an error after the ``"dn:"`` tag (single colon),
     *  - *dn_b64_error*: only set if there is an error after the ``"dn::"`` tag (double colon),
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
                        '(?:(?:'.self::URL.')?(?<value_url_error>'.self::NOT_SEP_X.'))'.
                    ')'.
                ')'.
            ')'.
        ')';

    /**
     * ATTRVAL_SPEC with enhanced error detection. TODO:
     */
    public const ATTRVAL_SPEC_X = '(?:'.self::ATTRIBUTE_DESCRIPTION.self::VALUE_SPEC_X.self::SEP_X.')';
}

// vim: syntax=php sw=4 ts=4 et:
