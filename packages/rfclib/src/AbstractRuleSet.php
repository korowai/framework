<?php
/**
 * @file src/AbstractRuleSet.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Rfc;

use Korowai\Lib\Rfc\RuleSetInterface;
use Korowai\Lib\Rfc\Traits\RulesFromConstants;

/**
 * Base class for Rfc classes that want to implement RuleSetInterface.
 */
abstract class AbstractRuleSet implements RuleSetInterface
{
    use RulesFromConstants;

    /**
     * Capture groups for every class that extends the AbstractRuleSet. Keys
     * are class names. Each value is an array with rule names as keys and
     * arrays of capture group names as values.
     */
    protected static $capturesPerClass = [];

    /**
     * Returns the array of captures for current class.
     *
     * @return array
     *      The array of captures for the class (or null) as assigned with
     *      *setClassCaptures()*.
     */
    protected static function getClassCaptures() : ?array
    {
        return self::$capturesPerClass[static::class] ?? null;
    }

    /**
     * Assigns *$captures* to the internal array of captures for current class.
     *
     * @param  array $captures
     */
    protected static function setClassCaptures(array $captures) : void
    {
        self::$capturesPerClass[static::class] = $captures;
    }

    /**
     * Unsets the class captures. After this, *getClassCaptures()* will return
     * null until *setClassCaptures()* will assign new class captures.
     */
    public static function unsetClassCaptures() : void
    {
        unset(self::$capturesPerClass[static::class]);
    }

    /**
     * Returns true, if capture group with name *$name* is an error-catching
     * capture group.
     *
     * @param  string $name
     * @return bool
     */
    public static function isErrorCapture(string $name) : bool
    {
        return substr_compare(strtolower($name), 'error', -5) == 0;
    }

    /**
     * {@inheritdoc}
     */
    public static function filterErrors(string $ruleName, array $matches) : array
    {
        return array_intersect_key($matches, static::errorCaptures($ruleName));
    }

    /**
     * {@inheritdoc}
     */
    public static function filterValues(string $ruleName, array $matches) : array
    {
        return array_intersect_key($matches, static::valueCaptures($ruleName));
    }
}

// vim: syntax=php sw=4 ts=4 et:
