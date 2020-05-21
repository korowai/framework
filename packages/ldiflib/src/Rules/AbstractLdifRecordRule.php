<?php
/**
 * @file src/Rules/AbstractLdifRecordRule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Ldif\Traits\AbstractLdifRecordNestedRules;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractLdifRecordRule implements RuleInterface
{
    use AbstractLdifRecordNestedRules;

    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     * @param  array $options
     */
    public function initAbstractLdifRecordRule(bool $tryOnly = false, array $options = [])
    {
        $rules = array_intersect_key($options, static::getNestedRulesSpecs());
        if (($dnSpecRule = ($rules['dnSpecRule'] ?? null)) === null) {
            $rules['dnSpecRule'] = new DnSpecRule($tryOnly);
        } elseif (!($dnSpecRule instanceof DnSpecRule)) {
            $given = is_object($dnSpecRule) ? get_class($dnSpecRule).' object' : gettype($dnSpecRule);
            $call = __class__.'::'.__function__.'($tryOnly, $options)';
            $message = 'Argument $options["dnSpecRule"] in '.$call.' must be an instance of '.
                       DnSpecRule::class.', '.$given.' given.';
            throw new InvalidRuleClassException($message);
        } elseif ($tryOnly !== $dnSpecRule->isOptional()) {
            $optional = $tryOnly ? 'true' : 'false';
            $call = __class__.'::'.__function__.'('.$optional.', $options)';
            $message = 'Argument $options in '.$call.' must satisfy '.
                       '$options["dnSpecRule"]->isOptional() === '.$optional.'.';
            // FIXME: dedicated exception
            throw new \InvalidArgumentException($message);
        }
        $this->initNestedRules($rules);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional() : bool
    {
        return $this->getDnSpecRule()->isOptional();
    }

    /**
     * Parses one or more occurences of attrval-spec.
     *
     * @param  State $state
     * @param  array $attrVals
     * @return bool
     */
    public function parseAttrValSpecs(State $state, array &$attrVals = null) : bool
    {
        if (!$this->getAttrValSpecReqRule()->parse($state, $attrVal0)) {
            return false;
        }

        $count = count($state->getErrors());
        $attrVals = array_merge([$attrVal0], Util::repeat($this->getAttrValSpecOptRule(), $state));
        return !(count($state->getErrors()) > $count);
    }
}
// vim: syntax=php sw=4 ts=4 et:
