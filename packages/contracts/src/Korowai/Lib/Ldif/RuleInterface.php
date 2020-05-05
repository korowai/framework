<?php
/**
 * @file src/Korowai/Lib/Ldif/RuleInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for cursor objects.
 */
interface RuleInterface extends \Korowai\Lib\Rfc\RuleInterface
{
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
    public function parse(ParserStateInterface $state, &$value = null) : bool;
}

// vim: syntax=php sw=4 ts=4 et:
