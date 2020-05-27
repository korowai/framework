<?php
/**
 * @file src/Korowai/Lib/Ldif/Rules/LdifFileRuleInterface.php
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
interface LdifFileRuleInterface extends RuleInterface
{
    /**
     * Returns the nested LdifContentRuleInteface.
     *
     * @return LdifContentRuleInterface
     */
    public function getLdifContentRule() : LdifContentRuleInterface;

    /**
     * Returns the nested LdifChangesRuleInteface.
     *
     * @return LdifChangesRuleInterface
     */
    public function getLdifChangesRule() : LdifChangesRuleInterface;
}

// vim: syntax=php sw=4 ts=4 et:
