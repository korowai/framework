<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\RuleInterface;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdifChangeRecordRuleInterface extends RuleInterface
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
     * Returns the nested ControlRuleInteface.
     *
     * @return ControlRuleInterface
     */
    public function getControlRule() : ControlRuleInterface;

    /**
     * Returns the nested ChangeRecordInitRuleInteface.
     *
     * @return ChangeRecordInitRuleInterface
     */
    public function getChangeRecordInitRule() : ChangeRecordInitRuleInterface;
}

// vim: syntax=php sw=4 ts=4 et:
