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

use Korowai\Lib\Ldif\Nodes\LdifAttrValRecord;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Ldif\Snippet;

/**
 * A rule object that parses *ldif-attrval-record* as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
 *
 * - semantic value: [LdifAttrValRecordInterface](\.\./Nodes/LdifAttrValRecordInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdifAttrValRecordRule extends AbstractLdifRecordRule
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
     */
    public function __construct(array $options = [])
    {
        $this->setControlRule($options['controlRule'] ?? new ControlRule());
        $this->setChangeRecordInitRule($options['changeRecordInitRule'] ?? new ChangeRecordInitRule());
        $this->setModSpecRule($options['modSpecRule'] ?? new ModSpecRule());
        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state, &$value = null, bool $trying = false): bool
    {
        $begin = $state->getCursor()->getClonedLocation();
        if (!$this->getDnSpecRule()->parse($state, $dn, $trying) ||
            !$this->getSepRule()->parse($state) ||
            !$this->getAttrValSpecRule()->repeat($state, $attrVals, 1)) {
            $value = null;

            return false;
        }

        $snippet = Snippet::createFromLocationAndState($begin, $state);
        $value = new LdifAttrValRecord($dn, $attrVals, compact('snippet'));

        return true;
    }

    /**
     * Returns the nested ControlRule object.
     */
    public function getControlRule(): ControlRule
    {
        return $this->controlRule;
    }

    /**
     * Sets new nested ControlRule object.
     *
     * @return object $this
     */
    public function setControlRule(ControlRule $rule)
    {
        $this->controlRule = $rule;

        return $this;
    }

    /**
     * Returns the nested ChangeRecordInitRule object.
     */
    public function getChangeRecordInitRule(): ChangeRecordInitRule
    {
        return $this->changeRecordInitRule;
    }

    /**
     * Sets new nested ChangeRecordInitRule object.
     *
     * @return object $this
     */
    public function setChangeRecordInitRule(ChangeRecordInitRule $rule)
    {
        $this->changeRecordInitRule = $rule;

        return $this;
    }

    /**
     * Returns the nested ModSpecRule object.
     */
    public function getModSpecRule(): ModSpecRule
    {
        return $this->modSpecRule;
    }

    /**
     * Sets new nested ModSpecRule object.
     *
     * @return object $this
     */
    public function setModSpecRule(ModSpecRule $rule)
    {
        $this->modSpecRule = $rule;

        return $this;
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
