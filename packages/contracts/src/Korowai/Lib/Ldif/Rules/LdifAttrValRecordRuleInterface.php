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

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdifAttrValRecordRuleInterface extends RuleInterface
{
    /**
     * Returns the nested DnSpecRuleInteface.
     *
     * @return DnSpecRuleInterface
     */
    public function getDnSpecRule() : DnSpecRuleInterface;

    /**
     * Returns the nested SepRuleInteface.
     *
     * @return SepRuleInterface
     */
    public function getSepRule() : SepRuleInterface;

    /**
     * Returns the nested AttrValSpecRuleInteface.
     *
     * @return AttrValSpecRuleInterface
     */
    public function getAttrValSpecRule() : AttrValSpecRuleInterface;
}

// vim: syntax=php sw=4 ts=4 et:
