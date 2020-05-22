<?php
/**
 * @file src/Rules/LdifAttrValRecordRule.php
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
use Korowai\Lib\Ldif\Traits\LdifAttrValRecordNestedRules;
use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\Records\AttrValRecord;

/**
 * A rule object that parses *ldif-attrval-record* as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
 *
 * - semantic value: [AttrValRecordInterface](\.\./Records/AttrValRecordInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdifAttrValRecordRule extends AbstractLdifRecordRule
{
    use LdifAttrValRecordNestedRules {
        getNestedRulesSpecs as getLdifAttrValRecordNestedRulesSpecs;
    }

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
        return array_merge(parent::getNestedRulesSpecs(), self::getLdifAttrValRecordNestedRulesSpecs());
    }

    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     * @param  array $options
     */
    public function __construct(bool $tryOnly = false, array $options = [])
    {
        $this->initAbstractLdifRecordRule($tryOnly, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state, &$value = null) : bool
    {
        $begin = $state->getCursor()->getClonedLocation();
        if (!$this->getDnSpecRule()->parse($state, $dn) ||
            !$this->getSepRule()->parse($state) ||
            !$this->parseAttrValSpecs($state, $attrVals)) {
            $value = null;
            return false;
        }

        $snippet = Snippet::createFromLocationAndState($begin, $state);
        $value = new AttrValRecord($dn, $attrVals, compact('snippet'));
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
