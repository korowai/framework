<?php
/**
 * @file src/Traits/ParsesWithRfcRule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Compat\Exception\PregException;
use Korowai\Lib\Rfc\RuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesWithRfcRule
{
    /**
     * Matches the string starting at $cursor's position against $pattern and
     * skips the whole match (moves the cursor after the matched part of
     * string).
     *
     * @param  string $pattern
     * @param  CursorInterface $cursor
     * @param  int $flags Passed to ``preg_match()`` (note: ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     * @return array Array of matches as returned by ``preg_match()``
     * @throws PregException When error occurs in ``preg_match()``
     */
    abstract public function matchAhead(string $pattern, CursorInterface $cursor, int $flags = 0) : array;

    /**
     * Matches the input substring starting at *$state*'s cursor against
     * regular expression provided by *$rule* and moves the cursor after
     * the end of the matched substring.
     *
     * @param  State $state
     *      The state provides cursor pointing to the offset of the beginning
     *      of the match. If the *$rule* matches anything, the *$state*'s
     *      cursor gets moved to the character next after the matched string.
     *      If *$rule* matches any errors, they will be appended to *$state*.
     * @param  RuleInterface $rule
     *      The rule to be used for matching.
     * @param  array $matches
     *      Returns matched captured groups including matched errors. If the
     *      rule doesn't match at all, the function returns empty *$matches*.
     *
     * @return bool
     *      Returns false if *$rule* doesn't match, or if the returned
     *      *$matches* include errors.
     */
    public function parseMatchRfcRule(State $state, RuleInterface $rule, array &$matches = null) : bool
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\G'.$rule.'/D', $cursor, PREG_UNMATCHED_AS_NULL);
        if (empty($matches)) {
            if (!$rule->isOptional()) {
                $message = $rule->getErrorMessage();
                $state->errorHere('syntax error: '.$message);
            }
            return false;
        }

        $errors = $rule->findCapturedErrors($matches);
        foreach ($errors as $errorKey => $errorMatch) {
            $message = $rule->getErrorMessage($errorKey);
            $state->errorAt($errorMatch[1], 'syntax error: '.$message);
        }

        return empty($errors);
    }

    /**
     * Parse using RFC rule and callback.
     *
     * @param  State $state
     * @param  RuleInterface $rule
     *      The RFC rule.
     * @param  callable $completion
     * A callback function to be invoked when the rule matches. The
     * prototype of the callback is
     *
     *      bool completion(ParserStateInterface $state, array $matches, &$value = null);
     *
     * The purpose of the completion function is to validate the captured
     * values (passed in via *$matches*) and optionally produce and return
     * to the caller any semantic value. The function shall return true on
     * success or false on failure.
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     *
     * @return bool
     */
    public function parseWithRfcRule(State $state, RuleInterface $rule, callable $completion, &$value = null) : bool
    {
        if (!$this->parseMatchRfcRule($state, $rule, $matches)) {
            $value = null;
            return false;
        }
        return call_user_func_array($completion, [$state, $matches, &$value]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
