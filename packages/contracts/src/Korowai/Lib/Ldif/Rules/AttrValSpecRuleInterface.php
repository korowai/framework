<?php
/**
 * @file src/Korowai/Lib/Ldif/Rules/AttrValSpecRuleInterface.php
 *
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
interface AttrValSpecRuleInterface extends RuleInterface
{
    /**
     * Returns the nested ValueSpecRuleInteface used for parsing *value-spec*
     * part of the *control* production.
     *
     * @return ValueSpecInterface
     */
    public function getValueSpecRule() : ValueSpecRuleInterface;
}

// vim: syntax=php sw=4 ts=4 et:
