<?php
/**
 * @file src/Traits/AbstractLdifRecordNestedRules.php
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
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;
use Korowai\Lib\Ldif\Exception\InvalidRuleNameException;
use Korowai\Lib\Ldif\Exception\NoRulesDefinedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AbstractLdifRecordNestedRules
{
    use HasNestedRules;

    /**
     * @var array
     */
    protected static $abstractLdifRecordNestedRulesSpecs = [
        'dnSpecRule'            => [
            'class'             => DnSpecRule::class,
            'optional'          => null,
            'construct'         => [true],
        ],
        'sepRule'               => [
            'class'             => SepRule::class,
            'optional'          => false,
            'construct'         => [false],
        ],
        'attrValSpecReqRule'    => [
            'class'             => AttrValSpecRule::class,
            'optional'          => false,
            'construct'         => [false],
        ],
        'attrValSpecOptRule'    => [
            'class'             => AttrValSpecRule::class,
            'optional'          => true,
            'construct'         => [true],
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
        return self::$abstractLdifRecordNestedRulesSpecs;
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
    public function getAttrValSpecReqRule() : AttrValSpecRule
    {
        return $this->getNestedRule('attrValSpecReqRule');
    }

    /**
     * Sets new nested AttrValSpecRule object.
     *
     * @param  AttrValSpecRule $rule
     * @return object $this
     */
    public function setAttrValSpecReqRule(AttrValSpecRule $rule)
    {
        return $this->setNestedRule('attrValSpecReqRule', $rule);
    }

    /**
     * Returns the nested AttrValSpecRule object.
     *
     * @return AttrValSpecRule
     */
    public function getAttrValSpecOptRule() : AttrValSpecRule
    {
        return $this->getNestedRule('attrValSpecOptRule');
    }

    /**
     * Sets new nested AttrValSpecRule object.
     *
     * @param  AttrValSpecRule $rule
     * @return object $this
     */
    public function setAttrValSpecOptRule(AttrValSpecRule $rule)
    {
        return $this->setNestedRule('attrValSpecOptRule', $rule);
    }
}

// vim: syntax=php sw=4 ts=4 et:
