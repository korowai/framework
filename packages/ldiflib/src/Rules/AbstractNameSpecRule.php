<?php
/**
 * @file src/Rules/AbstractNameSpecRule.php
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
use Korowai\Lib\Ldif\Scan;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\Rfc2849x;

/**
 * A rule object that implements *dn-spec* rule defined in RFC2849.
 *
 * - semantic value: string
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractNameSpecRule extends AbstractRfcRule
{
    /**
     * Returns name of capture group that captures base64-encoded name.
     *
     * @return string
     */
    public static function b64Capture() : string
    {
        return static::$b64Capture;
    }

    /**
     * Returns name of capture group that captures safe-string name.
     *
     * @return string
     */
    public static function safeCapture() : string
    {
        return static::$safeCapture;
    }

    /**
     * Returns name of the class ruleset class to be used.
     *
     * @return string
     */
    public static function rfcRuleSet() : string
    {
        return static::$rfcRuleSet;
    }

    /**
     * Returns the identifier of the rule from rfcRuleSet() to be used.
     *
     * @return string
     */
    public static function rfcRuleId() : string
    {
        return static::$rfcRuleId;
    }

    /**
     * Returns callable to be used to validate decoded name.
     *
     * @return callable
     */
    public static function validator() : callable
    {
        return static::$validator;
    }

    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     *      Passed to the constructor of RFC [Rule](\.\./\.\./Rfc/Rule.html)
     *      being created internally.
     */
    public function __construct(bool $tryOnly = false)
    {
        $this->setRfcRule(new Rule(static::rfcRuleSet(), static::rfcRuleId(), $tryOnly));
    }

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
     * @return bool true on success, false on failure.
     */
    public function parseMatched(State $state, array $matches, &$value = null) : bool
    {
        $b64 = static::b64Capture();
        $safe = static::safeCapture();

        if (Scan::matched($b64, $matches, $string, $offset)) {
            $value = Util::base64Decode($state, $string, $offset);
            if (!$this->utf8Check($state, $value, $offset)) {
                return false;
            }
            return $this->validate($state, $value, $offset);
        } elseif (Scan::matched($safe, $matches, $value, $offset)) {
            return $this->validate($state, $value, $offset);
        }

        // This may happen with broken Rfc rule.
        $value = null;
        $state->errorHere('internal error: missing or invalid capture groups "'.$safe.'" and "'.$b64.'"');
        return false;
    }

    /**
     * Checks UTF8 string using Util::utf8Check().
     *
     * @param  State $state
     * @param  string $value
     * @param  int $offset
     *
     * @return bool
     */
    protected static function utf8Check(State $state, ?string &$value, int $offset)
    {
        return static::checkWith([Util::class, 'utf8Check'], $state, $value, $offset);
    }

    /**
     * Checks DN string using Util::dnCheck().
     *
     * @param  State $state
     * @param  string $value
     * @param  int $offset
     *
     * @return bool
     */
    protected static function validate(State $state, string &$value, int $offset) : bool
    {
        return static::checkWith(static::validator(), $state, $value, $offset);
    }

    /**
     * Delegates value checking to *$func*.
     *
     * @param  callable $func
     * @param  State $state
     * @param  string $value
     * @param  int $offset
     *
     * @return bool
     */
    protected static function checkWith(callable $func, State $state, ?string &$value, int $offset)
    {
        if ($value === null) {
            return  false;
        }
        if (!call_user_func_array($func, [$state, $value, $offset])) {
            $value = null;
            return false;
        }
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
