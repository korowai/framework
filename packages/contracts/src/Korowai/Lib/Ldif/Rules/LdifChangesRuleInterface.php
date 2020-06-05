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
interface LdifChangesRuleInterface extends RuleInterface
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
     * Returns the nested LdifChangeRecordRuleInteface.
     *
     * @return LdifChangeRecordRuleInterface
     */
    public function getLdifChangeRecordRule() : LdifChangeRecordRuleInterface;
}

// vim: syntax=php sw=4 ts=4 et:
