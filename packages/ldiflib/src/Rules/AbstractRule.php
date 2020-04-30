<?php
/**
 * @file src/Rules/AbstractRule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Lib\Rfc\Traits\DecoratesRuleInterface;
use Korowai\Lib\Ldif\Scan;

/**
 * Base class for LDIF parsing rules. The LDIF rule decorates RFC
 * [RuleInterface](\.\./\.\./Rfc/RuleInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractRule implements RuleInterface
{
    use DecoratesRuleInterface;

    /**
     * Completes parsing with rule by validating substrings captured by the
     * rule (*$matches*) and forming semantic value out of *$matches*.
     *
     * The purpose of the *parseMatched()* method is to validate the captured
     * values (passed in via *$matches*) and optionally produce and return
     * to the caller any semantic *$value*. The function shall return true on
     * success or false on failure.
     *
     * @param  State $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  array $matches
     *      An array of matches as returned from *preg_match()*. Contains
     *      substrings captured by the encapsulated RFC rule.
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     */
    abstract public function parseMatched(State $state, array $matches, &$value = null) : bool;

    /**
     * Initializes the object.
     *
     * @param  RuleInterfce $rfcRule
     */
    public function __construct(RuleInterface $rfcRule)
    {
        $this->setRfcRule($rfcRule);
    }

    /**
     * Parse string starting at position defined by *$state*.
     *
     * There are three scenarios. The rule can either:
     *
     * - fail to match (the string does not match the rule at all),
     * - match with errors (string matched but error substrings are captured),
     * - match successfully.
     *
     * In the first two cases, the method will append errors to *$state*, set
     * *$value* to null and return false. The *parseMatched()* method is not
     * invoked. In the third case, the *parseMatched()* method is invoked and
     * it's return value is returned to caller.
     *
     * @param  State $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     *
     * @return bool Returns true on success or false on error.
     */
    public function parse(State $state, &$value = null) : bool
    {
        if (!$this->matchRfcRule($state, $this->getRfcRule(), $matches)) {
            $value = null;
            return false;
        }
        return $this->parseMatched($state, $matches, $value);
    }

    /**
     * Returns instance of [RuleInterface](\.\./\.\./Rfc/RuleInterface.html)
     * used to parse strings.
     *
     * @return RuleInterface
     */
    public function getRfcRule() : RuleInterface
    {
        return $this->rfcRule;
    }

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
    public static function matchRfcRule(State $state, RuleInterface $rule, array &$matches = null) : bool
    {
        $cursor = $state->getCursor();

        $matches = Scan::matchAhead('/\G'.$rule.'/D', $cursor, PREG_UNMATCHED_AS_NULL);
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
}
// vim: syntax=php sw=4 ts=4 et:
