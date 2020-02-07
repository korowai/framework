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
use Korowai\Lib\Rfc\Traits\RuleMethods;

/**
 * Base class for Rfc classes that want to implement RuleSetInterface.
 */
abstract class AbstractRuleSet implements RuleSetInterface
{
    use RuleMethods;

    protected static $capturesPerClass = [];

    /**
     * Setter accessor to the array of captures.
     *
     * @param  array $captures
     */
    protected static function setCaptures(array $captures) : void
    {
        self::$capturesPerClass[static::class] = $captures;
    }

    /**
     * Getter accessor to the array of captures.
     *
     * @param  array $captures
     */
    protected static function getCaptures() : ?array
    {
        return self::$capturesPerClass[static::class] ?? null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
