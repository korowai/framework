<?php
/**
 * @file Testing/Constraint/HasPregCaptures.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Constraint that accepts arrays of matches returned from ``preg_match()``
 * that has capture groups described in the array of expectations.
 *
 * Checks only entries present in the array of expectations. Special values may
 * be used in the expectations:
 *
 * - ``['foo' => false]`` asserts that group ``'foo'`` was not captured,
 * - ``['foo' => true]`` asserts that group ``'foo'`` was captured,
 * - ``['foo' => 'FOO']`` asserts that group ``'foo'`` was captured and it's value equals ``'FOO'``.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class HasPregCaptures extends Constraint
{
    /**
     * @var array
     */
    private $expected;

    /**
     * Initializes the constraint.
     *
     * @param  array $expected An array of expected values.
     */
    public function __construct(array $expected)
    {
        $this->expected = $expected;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
    {
        $expectations = array_map(function ($value) {
            return $value === true ? '<must exist>' : ($value === false ? '<must not exist>' : $value);
        }, $this->expected);
        return sprintf('has capture groups satisfying %s', $this->exporter()->export($expectations));
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    public function matches($other) : bool
    {
        if (!is_array($other)) {
            return false;
        }
        foreach ($this->expected as $key => $expected) {
            if (!$this->captureGroupMatches($expected, $other, $key)) {
                return false;
            }
        }
        return true;
    }

    protected function captureGroupMatches($expected, array $other, $key) : bool
    {
        $exists = ($other[$key] ?? [null, -1])[0] !== null;
        if ($expected === false) {
            return !$exists;
        } elseif ($expected === true) {
            return $exists;
        } else {
            return array_key_exists($key, $other) && ($other[$key] === $expected);
        }
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     */
    public function failureDescription($other) : string
    {
        return $this->exporter()->export($other).' '.$this->toString();
    }
}

// vim: syntax=php sw=4 ts=4 et:
