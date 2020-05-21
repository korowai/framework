<?php
/**
 * @file src/Rules/LdifChangeRecordRule.php
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
use Korowai\Lib\Ldif\Traits\LdifChangeRecordNestedRules;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\Records\AddRecord;
use Korowai\Lib\Ldif\Records\DeleteRecord;
use Korowai\Lib\Ldif\Records\ModDnRecord;
use Korowai\Lib\Ldif\Records\ModifyRecord;
use Korowai\Lib\Ldif\Records\ChangeRecordInterface;
use Korowai\Lib\Ldif\Records\AddRecordInterface;
use Korowai\Lib\Ldif\Records\DeleteRecordInterface;
use Korowai\Lib\Ldif\Records\ModDnRecordInterface;
use Korowai\Lib\Ldif\Records\ModifyRecordInterface;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;

/**
 * A rule object that parses *ldif-change-record* rule defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
 *
 * - semantic value: [ChangeRecordInterface](\.\./Records/ChangeRecordInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdifChangeRecordRule extends AbstractLdifRecordRule
{
    use LdifChangeRecordNestedRules {
        getNestedRulesSpecs as getLdifChangeRecordNestedRulesSpecs;
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
        return array_merge(parent::getNestedRulesSpecs(), self::getLdifChangeRecordNestedRulesSpecs());
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
            !$this->parseControls($state, $controls)
        ) {
            return false;
        }

        $vars = compact('begin', 'dn', 'controls');
        if (!$result = $this->parseRecord($state, $value, $vars)) {
            return false;
        }

        $snippet = Snippet::createFromLocationAndState($begin, $state);
        $value->setSnippet($snippet);
        return true;
    }

    /**
     * Parses the *changerecord* rule of the *ldif-change-record*.
     *
     * @param  State $state
     * @param  ChangeRecordInterface $record
     * @param  array $vars
     */
    public function parseRecord(State $state, ChangeRecordInterface &$record = null, array $vars = []) : bool
    {
        static $parsers = [
            'add'    => 'parseAdd',
            'delete' => 'parseDelete',
            'moddn'  => 'parseModDn',
            'modrdn' => 'parseModDn',
            'modify' => 'parseModify',
        ];

        if (!$this->getChangeRecordInitRule()->parse($state, $changeType)) {
            return false;
        }

        $vars['changeType'] = $changeType;

        if (($parser = $parsers[$changeType] ?? null) === null) {
            $state->errorHere('internal error: unsupported changeType: "'.$changeType.'"');
            $record = null;
            return false;
        }

        return $this->{$parser}($state, $record, $vars);
    }

    /**
     * @todo Write documentation
     */
    public function parseAdd(State $state, AddRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);
        if (!$this->parseAttrValSpecs($state, $attrValSpecs)) {
            return false;
        }
        $record = new AddRecord($dn, compact('controls', 'attrValSpecs'));
        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseDelete(State $state, DeleteRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);
        $record = new DeleteRecord($dn, compact('controls'));
        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseModDn(State $state, ModDnRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);

        throw new \BadMethodCallException('not implemented');

        $options = compact('controls', 'changeType', 'deleteOldRdn', 'newSuperior');
        $record = new ModDnRecord($dn, $newRdn, $options);
        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseModify(State $state, ModifyRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);
        if (!$this->parseModSpecs($state, $modSpecs)) {
            $record = null;
            return false;
        }
        $record = new ModifyRecord($dn, compact('controls', 'modSpecs'));
        return true;
    }

    /**
     * Parses sequence of zero or more *control*'s defined in RFC2849.
     *
     * @param  State $state
     * @param  array $controls
     * @return bool
     */
    public function parseControls(State $state, array &$controls = null) : bool
    {
        $count = count($state->getErrors());
        $controls = Util::repeat($this->getControlRule(), $state);
        return !(count($state->getErrors()) > $count);
    }

    /**
     * @todo Write documentation
     */
    public function parseModSpecs(State $state, array &$modSpecs = null) : bool
    {
        $count = count($state->getErrors());
        $modSpecs = Util::repeat($this->getModSpecRule(), $state);
        return !(count($state->getErrors()) > $count);
    }
}
// vim: syntax=php sw=4 ts=4 et:
