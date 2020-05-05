<?php
/**
 * @file src/Rules/VersionSpecRule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\AbstractRule;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Ldif\Scan;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\Rfc2849x;

/**
 * A rule that parses RFC2849 version-spec.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class VersionSpecRule extends AbstractRule
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
        $this->setRfcRule(new Rule(Rfc2849x::class, 'VERSION_SPEC_X', $tryOnly));
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
        if (Scan::matched('version_number', $matches, $string, $offset)) {
            if (($number = (int)$string) === 1) {
                $value = $number;
                return true;
            }
            $state->errorAt($offset, "syntax error: unsupported version number: $number");
            $value = null;
            return false;
        }

        // This may happen with broken Rfc2849x::VERSION_SPEC_X rule.
        $value = null;
        $state->errorHere('internal error: missing or invalid capture group "version_number"');
        return false;
    }
}
// vim: syntax=php sw=4 ts=4 et:
