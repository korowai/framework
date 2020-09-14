<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\Nodes\LdifAddRecord;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecord;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecord;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecord;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;

/**
 * A rule object that parses *ldif-change-record* as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
 *
 * - semantic value: [LdifChangeRecordInterface](\.\./Nodes/LdifChangeRecordInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdifChangeRecordRule extends AbstractLdifRecordRule
{
    /**
     * @var ControlRule
     */
    private $controlRule;

    /**
     * @var ChangeRecordInitRule
     */
    private $changeRecordInitRule;

    /**
     * @var ModSpecRule
     */
    private $modSpecRule;

    /**
     * Initializes the object.
     *
     * @param  array $options
     */
    public function __construct(array $options = [])
    {
        $this->setControlRule($options['controlRule'] ?? new ControlRule);
        $this->setChangeRecordInitRule($options['changeRecordInitRule'] ?? new ChangeRecordInitRule);
        $this->setModSpecRule($options['modSpecRule'] ?? new ModSpecRule);
        parent::__construct($options);
    }

    /**
     * Returns the nested ControlRule object.
     *
     * @return ControlRule
     */
    public function getControlRule() : ?ControlRule
    {
        return $this->controlRule;
    }

    /**
     * Sets new nested ControlRule object.
     *
     * @param  ControlRule $rule
     * @return object $this
     */
    public function setControlRule(ControlRule $rule)
    {
        $this->controlRule = $rule;
        return $this;
    }

    /**
     * Returns the nested ChangeRecordInitRule object.
     *
     * @return ChangeRecordInitRule
     */
    public function getChangeRecordInitRule() : ?ChangeRecordInitRule
    {
        return $this->changeRecordInitRule;
    }

    /**
     * Sets new nested ChangeRecordInitRule object.
     *
     * @param  ChangeRecordInitRule $rule
     * @return object $this
     */
    public function setChangeRecordInitRule(ChangeRecordInitRule $rule)
    {
        $this->changeRecordInitRule = $rule;
        return $this;
    }

    /**
     * Returns the nested ModSpecRule object.
     *
     * @return ModSpecRule
     */
    public function getModSpecRule() : ?ModSpecRule
    {
        return $this->modSpecRule;
    }

    /**
     * Sets new nested ModSpecRule object.
     *
     * @param  ModSpecRule $rule
     * @return object $this
     */
    public function setModSpecRule(ModSpecRule $rule)
    {
        $this->modSpecRule = $rule;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state, &$value = null, bool $trying = false) : bool
    {
        $begin = $state->getCursor()->getClonedLocation();
        if (!$this->getDnSpecRule()->parse($state, $dn, $trying) ||
            !$this->getSepRule()->parse($state) ||
            !$this->getControlRule()->repeat($state, $controls) ||
            !$this->parseRecord($state, $value, compact('dn', 'controls'))) {
            $value = null;
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
     * @param  LdifChangeRecordInterface $record
     * @param  array $vars
     */
    protected function parseRecord(State $state, LdifChangeRecordInterface &$record = null, array $vars = []) : bool
    {
        static $parsers = [
            'add'    => 'parseAdd',
            'delete' => 'parseDelete',
            'moddn'  => 'parseModDn',
            'modrdn' => 'parseModDn',
            'modify' => 'parseModify',
        ];

        if (!$this->getChangeRecordInitRule()->parse($state, $changeType)) {
            $record = null;
            return false;
        }

        $vars['changeType'] = $changeType;

        if (($parser = $parsers[$changeType] ?? null) === null) {
            $state->errorHere('internal error: unsupported changeType: "'.$changeType.'"');
            return false;
        }

        return $this->{$parser}($state, $record, $vars);
    }

    /**
     * @todo Write documentation
     */
    protected function parseAdd(State $state, LdifAddRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);
        if (!$this->getAttrValSpecRule()->repeat($state, $attrValSpecs, 1)) {
            return false;
        }
        $record = new LdifAddRecord($dn, compact('controls', 'attrValSpecs'));
        return true;
    }

    /**
     * @todo Write documentation
     */
    protected function parseDelete(State $state, LdifDeleteRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);
        $record = new LdifDeleteRecord($dn, compact('controls'));
        return true;
    }

    /**
     * @todo Write documentation
     */
    protected function parseModDn(State $state, LdifModDnRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);

        throw new \BadMethodCallException('not implemented');

        $options = compact('controls', 'changeType', 'deleteOldRdn', 'newSuperior');
        $record = new LdifModDnRecord($dn, $newRdn, $options);
        return true;
    }

    /**
     * @todo Write documentation
     */
    protected function parseModify(State $state, LdifModifyRecordInterface &$record = null, array $vars = []) : bool
    {
        extract($vars);
        if (!$this->getModSpecRule()->repeat($state, $modSpecs)) {
            return false;
        }
        $record = new LdifModifyRecord($dn, compact('controls', 'modSpecs'));
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
