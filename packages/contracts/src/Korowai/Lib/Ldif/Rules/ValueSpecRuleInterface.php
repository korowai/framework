<?php
/**
 * @file src/Korowai/Lib/Ldif/Rules/ValueSpecRuleInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ValueSpecRuleInterface extends RuleInterface
{
    /**
     * Parse string starting at position defined by *$state*.
     *
     * Four cases should be considered:
     *
     * 1. The rule fails to match the string (for example, the examined string
     *    doesn't start with expected keyword).
     * 2. The rule matches the string with errors (for example, the examined
     *    string starts with expected keyword but contains errors in the part
     *    following the keyword).
     * 3. The rule matches without errors, but the semantic value does not pass
     *    internal validation.
     * 4. The rule matches without errors and semantic value passes internal
     *    validation.
     *
     * In cases 1 .. 3, the function sets *$value = null* and returns
     * ``false``. In 4, the function returns ``true``. Errors are always
     * appended to *$state* in cases 2, 3. In case 1, error is appended to
     * *$state* only if *$trying === false* (default).
     *
     * @param  ParserStateInterface $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  ValueSpecInterface $value
     *      Semantic value to be returned to caller.
     * @param  bool $trying
     *      If ``false``, error is appended to *$state* when the rule does not match.
     *
     * @return bool Returns true on success or false on error.
     */
    public function parse(
        ParserStateInterface $state,
        ValueSpecInterface &$value = null,
        bool $trying = false
    ) : bool;
}

// vim: syntax=php sw=4 ts=4 et:
