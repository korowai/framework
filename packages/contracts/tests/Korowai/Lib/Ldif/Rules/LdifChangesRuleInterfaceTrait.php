<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Tests\Lib\Ldif\RuleInterfaceTrait;
use Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\LdifChangeRecordRuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifChangesRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $versionSpecRule = null;
    public $sepRule = null;
    public $ldifChangeRecordRule = null;

    public function getVersionSpecRule() : VersionSpecRuleInterface
    {
        return $this->versionSpecRule;
    }

    public function getSepRule() : SepRuleInterface
    {
        return $this->sepRule;
    }

    public function getLdifChangeRecordRule() : LdifChangeRecordRuleInterface
    {
        return $this->ldifChangeRecordRule;
    }
}

// vim: syntax=php sw=4 ts=4 et:
