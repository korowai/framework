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
 * Extensions to [RFC2849](https://tools.ietf.org/html/rfc2849).
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
    public const NOT_SEP_X = '(?:[^'.self::CR.self::LF.']|'.self::CR.'(?!'.self::LF.'))';

    /**
     * [Rfc2849::VERSION_SPEC](Rfc2849.html) with error detection.
     *
     * Matches any string that starts with ``"version:"`` and spans to the
     * nearest [Rfc::2849x::SEP_X](Rfc2849x.html). Returns ``version_error``
     * capture group if there is an error after the ``"version:"`` tag.
     *
     * Capture groups:
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
     * Matches any string that starts with ``"dn:"``, and spans to the nearest
     * [Rfc2849x::SEP_X](Rfc2849x.html). Returns one of the ``dn_*_error``
     * capture groups if there is an error after the ``"version:"`` tag.
     *
     * Capture groups:
     *
     *  - ``dn_safe``: only set if the subject contains no syntax errors and the initial tag is ``"dn:"``,
     *  - ``dn_b64``: only set if the subject contains no syntax errors and the initial tag is ``"dn::"``,
     *  - ``dn_safe_error``: only set if there is an error after the ``"dn:"``
     *    tag (single colon); contains the substring that failed to match [Rfc2849::SAFE_STRING](Rfc2849.html),
     *  - ``dn_b64_error``: only set if there is an error after the ``"dn::"``
     *    tag (double colon); contains the substring that failed to match [Rfc2849::BASE64_STRING](Rfc2849.html),
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
     * [Rfc2849::VALUE_SPEC](Rfc2849.html) with enhanced error detection.
     *
     * Matches any string that starts with one of ``":"``, ``"::"`` or ``":<"``
     * and spans to the nearest [Rfc2849x::SEP_X](Rfc2849x.html). Returns one of
     * the ``value_*_error`` capture groups (below) if there is an error after
     * the ``":"``/``"::"``/``":<"`` tag.
     *
     * Capture groups:
     *
     *  - ``value_safe``: only set if the value-spec specifies SAFE-STRING using
     *    single colon ``":"`` notation and there is no error in the string,
     *  - ``value_b64``: only set if the value-space specifies BASE64-STRING
     *    using double colon ``"::"`` notation and there is no error in the
     *    string,
     *  - ``value_url``: only set if the value-spec specifies URL using
     *    colon-less-than ``":<"`` notation and there is no error in the
     *    string,
     *  - ``value_safe_error``: only set if there is an error after ``":"`` tag
     *    (single colon); contains the string that failed to match [Rfc2849::SAFE_STRING](Rfc2849.html),
     *  - ``value_b64_error``: only set if there is an error after ``"::"`` tag
     *    (double colon); contains the string that failed to match [Rfc2849::BASE64_STRING](Rfc2849.html),
     *  - ``value_url_error``: only set if there is an error after ``":<"`` tag
     *    (colon less-than); contains the string that failed to match [Rfc2849::URL](Rfc2849.html),
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
                    '(?:(?J)'.
                        '(?<value_url>'.self::URL.'(?='.self::SEP_X.'))'.
                        '|'.
                        '(?:(?:'.self::URL.')?(?<value_url_error>'.self::NOT_SEP_X.'*))'.
                    ')'.
                ')'.
            ')'.
        ')';

    /**
     * [Rfc2849::CONTROL](Rfc2849.html) with enhanced error detection.
     *
     * Matches any string that starts with ``"control:"`` tag and spans to the
     * nearest [Rfc2849x::SEP_X](Rfc2849x.html).
     *
     * Capture groups:
     *
     *  - ``ctl_type``: only set if the matched string has no errors; contains OID of the control,
     *  - ``ctl_crit``: only set if the matched string defines criticality as ``true`` or ``false`` and does not
     *    contain any errors,
     *  - ``value_safe``: only set if the matched string specifies SAFE-STRING value and does not contain any errors,
     *  - ``value_b64``: only set if the matched string specifies BASE64-STRING value and does not contain any errors,
     *  - ``value_url``: only set if the matched string specifies URL value and does ont contain any errors,
     *  - ``ctl_type_error``: only set if the control type is missing or is not a properly formed OID,
     *  - ``ctl_crit_error``: only set if the control criticality is invalid (other than ``"true"`` or ``"false"``),
     *  - ``value_safe_error``: only set if the matched string specifies malformed SAFE-STRING as a value,
     *  - ``value_b64_error``: only set if the matched string specifies malformed BASE64-STRING as a value,
     *  - ``value_url_error``: only set if the matched string specifies malformed URL as a value.
     */
    public const CONTROL_X =
        '(?:'.
            'control:'.self::FILL.'(?:(?:'.
                '(?<ctl_type>'.self::LDAP_OID.')'.
                '(?:'.self::SPACE.'+(?<ctl_crit>true|false))?'.
                '(?:'.self::VALUE_SPEC_X.')?'.
                '(?='.self::SEP_X.')'.
            ')|(?:'.
                '(?:'.self::LDAP_OID.')'.
                '(?:'.self::SPACE.'+)'.
                '(?<ctl_crit_error>'.self::NOT_SEP_X.'*)'.
                '(?='.self::SEP_X.')'.
            ')|(?:'.
                '(?:'.self::LDAP_OID.')?'.
                '(?<ctl_type_error>'.self::NOT_SEP_X.'*)'.
                '(?='.self::SEP_X.')'.
            '))'.
            self::SEP_X.
        ')';

    /**
     * [Rfc2849::ATTRVAL_SPEC](Rfc2849.html) with enhanced error detection.
     *
     * Capture groups:
     *
     *  - ``attr_desc``: always set, contains the attribute description (attribute type with options),
     *  - ``value_safe``: only set if the value-spec specifies SAFE-STRING using
     *    single colon ``":"`` notation and there is no error in the string,
     *  - ``value_b64``: only set if the value-space specifies BASE64-STRING
     *    using double colon ``"::"`` notation and there is no error in the
     *    string,
     *  - ``value_url``: only set if the value-spec specifies URL using
     *    colon-less-than ``":<"`` notation and there is no error in the
     *    string,
     *  - ``value_safe_error``: only set if there is an error after ``":"`` tag
     *    (single colon); contains the string that failed to match [Rfc2849::SAFE_STRING](Rfc2849.html),
     *  - ``value_b64_error``: only set if there is an error after ``"::"`` tag
     *    (double colon); contains the string that failed to match [Rfc2849::BASE64_STRING](Rfc2849.html),
     *  - ``value_url_error``: only set if there is an error after ``":<"`` tag
     *    (colon less-than); contains the string that failed to match [Rfc2849::URL](Rfc2849.html),
     */
    public const ATTRVAL_SPEC_X = '(?:'.self::ATTRIBUTE_DESCRIPTION.self::VALUE_SPEC_X.self::SEP_X.')';

    /**
     * [Rfc2849::MOD_SPEC_INIT](Rfc2849.html) with enhanced error detection.
     *
     * Capture groups:
     *
     *  - ``mod_type``: always set, contains the modification type indicator,
     *    either ``"add"``, ``"delete"``, or ``"replace"``,
     *  - ``attr_desc``: only set if the rule matched and there is no error in
     *    *attributeDescripton*; contains the attribute description (attribute
     *    type with options).
     *  - ``attr_type_error``: only set if there is an error in the
     *    *AttributeType* part of the *attributeDescription*.
     *  - ``attr_opts_error``: only set if there is an error in the *options*
     *    part of the *attributeDescription*,
     */
    public const MOD_SPEC_INIT_X =
        '(?:'.
            '(?<mod_type>add|delete|replace):'.
            self::FILL.
            '(?:'.
                '(?:'.
                    self::ATTRIBUTE_DESCRIPTION.
                    '(?:'.self::SEP_X.')'.
                ')'.
                '|'.
                '(?:'.
                    self::ATTRIBUTE_TYPE.
                    '(?:;'.self::OPTIONS.'?)'.
                    '(?<attr_opts_error>'.self::NOT_SEP_X.'*)'.
                    '(?:'.self::SEP_X.')'.
                ')'.
                '|'.
                '(?:'.
                    self::ATTRIBUTE_TYPE.'?'.
                    '(?<attr_type_error>'.self::NOT_SEP_X.'*'.')'.
                    '(?:'.self::SEP_X.')'.
                ')'.
            ')'.
        ')';

    /**
     * Defined named capture groups that appear in patterns of the Rfc2849x
     * class.
     */
    protected static $rfc2849xRules = [
        'SEP_X',
        'NOT_SEP_X',
        'VERSION_SPEC_X',
        'DN_SPEC_X',
        'VALUE_SPEC_X',
        'CONTROL_X',
        'ATTRVAL_SPEC_X',
        'MOD_SPEC_INIT_X',
    ];

    /**
     * Defined error messages related to patterns from the Rfc2849x class.
     */
    protected static $rfc2849xErrors = [
        ''                  => [
            'VERSION_SPEC_X'    => 'expected "version:" (RFC2849)',
            'DN_SPEC_X'         => 'expected "dn:" (RFC2849)',
            'VALUE_SPEC_X'      => 'expected ":" (RFC2849)',
            'CONTROL_X'         => 'expected "control:" (RFC2849)',
            'ATTRVAL_SPEC_X'    => 'expected <AttributeDescription>":" (RFC2849)',
            'MOD_SPEC_INIT_X'   => 'expected one of "add:", "delete:" or "replace:" (RFC2849)',
        ],
        'attr_opts_error'   => 'missing or invalid options (RFC2849)',
        'attr_type_error'   => 'missing or invalid AttributeType (RFC2849)',
        'dn_b64_error'      => 'malformed BASE64-STRING (RFC2849)',
        'dn_safe_error'     => 'malformed SAFE-STRING (RFC2849)',
        'ctl_type_error'    => 'missing or invalid OID (RFC2849)',
        'ctl_crit_error'    => 'expected "true" or "false" (RFC2849)',
        'value_b64_error'   => 'malformed BASE64-STRING (RFC2849)',
        'value_safe_error'  => 'malformed SAFE-STRING (RFC2849)',
        'value_url_error'   => 'malformed URL (RFC2849/RFC3986)',
        'version_error'     => 'expected valid version number (RFC2849)',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getClassRuleNames() : array
    {
        return array_merge(self::$rfc2849xRules, parent::getClassRuleNames());
    }

    /**
     * {@inheritdoc}
     */
    public static function getDefinedErrors() : array
    {
        return array_merge_recursive(parent::getDefinedErrors(), self::$rfc2849xErrors);
    }
}

// vim: syntax=php sw=4 ts=4 et:
