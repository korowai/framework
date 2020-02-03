<?php
/**
 * @file src/functions/preg.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/compatlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Compat;

// @codingStandardsIgnoreStart
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

/**
 * Like [preg_filter()](https://www.php.net/manual/en/function.preg-filter.php),
 * but throws exception instead of returning errors.
 *
 * @param  mixed $pattern
 * @param  mixed $replacement
 * @param  string $subject
 * @param  int $limit
 * @param  int $count
 *
 * @return mixed
 *
 * @throws PregException
 */
function preg_filter(
    $pattern,
    $replacement,
    $subject,
    int $limit = -1,
    int &$count = null
) {
    $args = func_get_args();
    if (count($args) >= 5) {
        $args[4] = &$count;
    }
    return Preg::callPregFunc('\preg_filter', $args, 1);
}

/**
 * Like [preg_grep()](https://www.php.net/manual/en/function.preg-grep.php),
 * but throws exception instead of returning errors.
 *
 * @param  mixed $pattern
 * @param  mixed $replacement
 * @param  int $flags
 *
 * @return array
 *
 * @throws PregException
 */
function preg_grep(
    string $pattern,
    array $input,
    int $flags = 0
) : array {
    $args = func_get_args();
    return Preg::callPregFunc('\preg_grep', $args, 1);
}

/**
 * Like [preg_match()](https://www.php.net/manual/en/function.preg-match.php),
 * but throws exception instead of returning errors.
 *
 * @param  string $pattern
 * @param  string $subject
 * @param  array $matches
 * @param  int $flags
 * @param  int $offset
 *
 * @return int
 *
 * @throws PregException
 */
function preg_match(
    string $pattern,
    string $subject,
    array &$matches = null,
    int $flags = 0,
    int $offset = 0
) {
    $args = func_get_args();
    if (count($args) >= 3) {
        $args[2] = &$matches;
    }
    return Preg::callPregFunc('\preg_match', $args, 1);
}

/**
 * Like [preg_match_all()](https://www.php.net/manual/en/function.preg-match-all.php),
 * but throws exception instead of returning errors.
 *
 * @param  string $pattern
 * @param  string $subject
 * @param  array $matches
 * @param  int $flags
 * @param  int $offset
 *
 * @return int
 *
 * @throws PregException
 */
function preg_match_all(
    string $pattern,
    string $subject,
    array &$matches = null,
    int $flags = PREG_PATTERN_ORDER,
    int $offset = 0
) {
    $args = func_get_args();
    if (count($args) >= 3) {
        $args[2] = &$matches;
    }
    return Preg::callPregFunc('\preg_match_all', $args, 1);
}

/**
 * Like [preg_replace()](https://www.php.net/manual/en/function.preg-replace.php),
 * but throws exception instead of returning errors.
 *
 * @param  mixed $pattern
 * @param  mixed $replacement
 * @param  string $subject
 * @param  int $limit
 * @param  int $count
 *
 * @return mixed
 *
 * @throws PregException
 */
function preg_replace(
    $pattern,
    $replacement,
    $subject,
    int $limit = -1,
    int &$count = null
) {
    $args = func_get_args();
    if (count($args) >= 5) {
        $args[4] = &$count;
    }
    return Preg::callPregFunc('\preg_replace', $args, 1);
}

/**
 * Like [preg_replace_callback()](https://www.php.net/manual/en/function.preg-replace-callback.php),
 * but throws exception instead of returning errors.
 *
 * @param  mixed $pattern
 * @param  callable $callback
 * @param  string $subject
 * @param  int $limit
 * @param  int $count
 *
 * @return mixed
 *
 * @throws PregException
 */
function preg_replace_callback(
    $pattern,
    callable $callback,
    $subject,
    int $limit = -1,
    int &$count = null
) {
    $args = func_get_args();
    if (count($args) >= 5) {
        $args[4] = &$count;
    }
    return Preg::callPregFunc('\preg_replace_callback', $args, 1);
}

/**
 * Like [preg_replace_callback_array()](https://www.php.net/manual/en/function.preg-replace-callback-array.php),
 * but throws exception instead of returning errors.
 *
 * @param  array $patterns_and_callbacks
 * @param  string $subject
 * @param  int $limit
 * @param  int $count
 *
 * @return mixed
 *
 * @throws PregException
 */
function preg_replace_callback_array(
    array $patterns_and_callbacks,
    $subject,
    int $limit = -1,
    int &$count = null
) {
    $args = func_get_args();
    if (count($args) >= 4) {
        $args[3] = &$count;
    }
    return Preg::callPregFunc('\preg_replace_callback_array', $args, 1);
}

/**
 * Like [preg_split()](https://www.php.net/manual/en/function.preg-split.php),
 * but throws exception instead of returning errors.
 *
 * @param  string $pattern
 * @param  string $subject
 * @param  array $matches
 * @param  int $flags
 * @param  int $offset
 *
 * @return int
 *
 * @throws PregException
 */
function preg_split(
    string $pattern,
    string $subject,
    int $limit = -1,
    int $flags = 0
) {
    $args = func_get_args();
    return Preg::callPregFunc('\preg_split', $args, 1);
}

// phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
// @codingStandardsIgnoreEnd

// vim: syntax=php sw=4 ts=4 et:
