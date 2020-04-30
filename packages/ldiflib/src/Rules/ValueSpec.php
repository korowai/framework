<?php
/**
 * @file src/Rules/ValueSpec.php
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
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\Value;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\Rfc2849x;

/**
 * A rule that parses RFC2849 value-spec.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ValueSpec extends AbstractRule
{
    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     *      Passed to the constructor of RFC [Rule](\.\./\.\./Rfc/Rule.html)
     *      being created internally.
     */
    public function __construct(bool $tryOnly = false)
    {
        parent::__construct(new Rule(Rfc2849x::class, 'VALUE_SPEC_X', $tryOnly));
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
     */
    public function parseMatched(State $state, array $matches, &$value = null) : bool
    {
        if (Scan::matched('value_safe', $matches, $string, $offset)) {
            $value = Value::createSafeString($string);
            return true;
        } elseif (Scan::matched('value_b64', $matches, $string, $offset)) {
            $decoded = Util::base64Decode($state, $string, $offset);
            $value = Value::createBase64String($string, $decoded);
            return ($decoded !== null);
        } elseif (Scan::matched('value_url', $matches, $string, $offset)) {
            return $this->parseMatchedUriReference($state, $matches, $value);
        }

        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
        $state->errorHere($message);
        $value = null;
        return false;
    }

    /**
     * Make URI reference
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $value
     *
     * @return bool
     */
    public static function parseMatchedUriReference(State $state, array $matches, ValueInterface &$value = null) : bool
    {
        try {
            $value = Value::createUriFromRfc3986Matches($matches);
        } catch (UriSyntaxError $e) {
            $state->errorHere('syntax error: in URL: '.$e->getMessage());
            $value = null;
            return false;
        }
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
