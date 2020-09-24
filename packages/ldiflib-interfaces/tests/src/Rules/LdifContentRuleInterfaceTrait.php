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

use Korowai\Lib\Ldif\Rules\LdifAttrValRecordRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface;
use Korowai\Tests\Lib\Ldif\RuleInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifContentRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $versionSpecRule;
    public $sepRule;
    public $ldifAttrValRecordRule;

    public function getVersionSpecRule(): VersionSpecRuleInterface
    {
        return $this->versionSpecRule;
    }

    public function getSepRule(): SepRuleInterface
    {
        return $this->sepRule;
    }

    public function getLdifAttrValRecordRule(): LdifAttrValRecordRuleInterface
    {
        return $this->ldifAttrValRecordRule;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
