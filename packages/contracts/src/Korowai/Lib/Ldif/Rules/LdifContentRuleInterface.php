<?php
/**
 * @file src/Korowai/Lib/Ldif/Rules/LdifContentRuleInterface.php
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
interface LdifContentRuleInterface extends RuleInterface
{
    /**
     * Returns the nested VersionSpecRuleInteface.
     *
     * @return VersionSpecRuleInterface
     */
    public function getVersionSpecRule() : VersionSpecRuleInterface;

    /**
     * Returns the nested SepRuleInteface.
     *
     * @return SepRuleInterface
     */
    public function getSepRule() : SepRuleInterface;

    /**
     * Returns the nested LdifAttrValRecordRuleInteface.
     *
     * @return LdifAttrValRecordRuleInterface
     */
    public function getLdifAttrValRecordRule() : LdifAttrValRecordRuleInterface;
}

// vim: syntax=php sw=4 ts=4 et:
