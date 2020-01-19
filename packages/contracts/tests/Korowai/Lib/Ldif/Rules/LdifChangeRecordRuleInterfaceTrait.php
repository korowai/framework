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
use Korowai\Lib\Ldif\Rules\DnSpecRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\ControlRuleInterface;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifChangeRecordRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $dnSpecRule = null;
    public $sepRule = null;
    public $controlRule = null;
    public $changeRecordInitRule = null;

    public function getDnSpecRule() : DnSpecRuleInterface
    {
        return $this->dnSpecRule;
    }

    public function getSepRule() : SepRuleInterface
    {
        return $this->sepRule;
    }

    public function getControlRule() : ControlRuleInterface
    {
        return $this->controlRule;
    }

    public function getChangeRecordInitRule() : ChangeRecordInitRuleInterface
    {
        return $this->changeRecordInitRule;
    }
}

// vim: syntax=php sw=4 ts=4 et:
