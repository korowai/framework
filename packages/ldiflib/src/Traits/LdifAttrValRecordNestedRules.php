<?php
/**
 * @file src/Traits/LdifAttrValRecordNestedRules.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\AttrValRecordInitRule;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifAttrValRecordNestedRules
{
    /**
     * @var array
     */
    protected static $ldifAttrValRecordNestedRulesSpecs = [
    ];

    /**
     * Returns an array of nested rule specifications for the given class.
     *
     * @return array
     *      Returns array of key => value pairs, where keys are names of nested
     *      rules, unique within the class, and values are arrays of the
     *      following key => value options:
     *
     * - ``class`` (string): name of the class implementing given rule, the
     *   class itself must implement [RuleInterface](\.\./RuleInterface.html),
     * - ``construct`` (?array): if set, provides argument values to be passed
     *   to rule's constructor when creating the rule during default
     *   initialization,
     * - ``optional`` (?bool): if set, then the class ensures that the given
     *   nested *$rule* satisfies ``$rule->isOptional() === $optional``.
     */
    public static function getNestedRulesSpecs() : array
    {
        return self::$ldifAttrValRecordNestedRulesSpecs;
    }
}

// vim: syntax=php sw=4 ts=4 et:
