<?php
/**
 * @file src/Traits/LdifChangeRecordNestedRules.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifChangeRecordNestedRules
{
    use HasNestedRules;

    /**
     * @var array
     */
    protected static $ldifChangeRecordNestedRulesSpecs = [
        'dnSpecRule'            => [
            'class'             => DnSpecRule::class,
            'optional'          => null,
            'construct'         => [true],
        ],
        'controlRule'           => [
            'class'             => ControlRule::class,
            'optional'          => true,
            'construct'         => [true],
        ],
        'changeRecordInitRule'  => [
            'class'             => ChangeRecordInitRule::class,
            'optional'          => false,
            'construct'         => [false],
        ],
        'modSpecRule'           => [
            'class'             => ModSpecRule::class,
            'optional'          => true,
            'construct'         => [true],
        ],
        'sepRule'               => [
            'class'             => SepRule::class,
            'optional'          => false,
            'construct'         => [false],
        ],
        'attrValSpecRule'       => [
            'class'             => AttrValSpecRule::class,
            'optional'          => false,
            'construct'         => [false],
        ],
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
        return self::$ldifChangeRecordNestedRulesSpecs;
    }

    /**
     * Returns the nested DnSpecRule object.
     *
     * @return DnSpecRule
     */
    public function getDnSpecRule() : DnSpecRule
    {
        return $this->getNestedRule('dnSpecRule');
    }

    /**
     * Sets new nested DnSpecRule object.
     *
     * @param  DnSpecRule $rule
     * @return object $this
     */
    public function setDnSpecRule(DnSpecRule $rule)
    {
        return $this->setNestedRule('dnSpecRule', $rule);
    }

    /**
     * Returns the nested ControlRule object.
     *
     * @return ControlRule
     */
    public function getControlRule() : ControlRule
    {
        return $this->getNestedRule('controlRule');
    }

    /**
     * Sets new nested ControlRule object.
     *
     * @param  ControlRule $rule
     * @return object $this
     */
    public function setControlRule(ControlRule $rule)
    {
        return $this->setNestedRule('controlRule', $rule);
    }

    /**
     * Returns the nested ChangeRecordInitRule object.
     *
     * @return ChangeRecordInitRule
     */
    public function getChangeRecordInitRule() : ChangeRecordInitRule
    {
        return $this->getNestedRule('changeRecordInitRule');
    }

    /**
     * Sets new nested ChangeRecordInitRule object.
     *
     * @param  ChangeRecordInitRule $rule
     * @return object $this
     */
    public function setChangeRecordInitRule(ChangeRecordInitRule $rule)
    {
        return $this->setNestedRule('changeRecordInitRule', $rule);
    }

    /**
     * Returns the nested ModSpecRule object.
     *
     * @return ModSpecRule
     */
    public function getModSpecRule() : ModSpecRule
    {
        return $this->getNestedRule('modSpecRule');
    }

    /**
     * Sets new nested ModSpecRule object.
     *
     * @param  ModSpecRule $rule
     * @return object $this
     */
    public function setModSpecRule(ModSpecRule $rule)
    {
        return $this->setNestedRule('modSpecRule', $rule);
    }

    /**
     * Returns the nested SepRule object.
     *
     * @return SepRule
     */
    public function getSepRule() : SepRule
    {
        return $this->getNestedRule('sepRule');
    }

    /**
     * Sets new nested SepRule object.
     *
     * @param  SepRule $rule
     * @return object $this
     */
    public function setSepRule(SepRule $rule)
    {
        return $this->setNestedRule('sepRule', $rule);
    }

    /**
     * Returns the nested AttrValSpecRule object.
     *
     * @return AttrValSpecRule
     */
    public function getAttrValSpecRule() : AttrValSpecRule
    {
        return $this->getNestedRule('attrValSpecRule');
    }

    /**
     * Sets new nested AttrValSpecRule object.
     *
     * @param  AttrValSpecRule $rule
     * @return object $this
     */
    public function setAttrValSpecRule(AttrValSpecRule $rule)
    {
        return $this->setNestedRule('attrValSpecRule', $rule);
    }
}

// vim: syntax=php sw=4 ts=4 et:
