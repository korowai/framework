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
use Korowai\Lib\Ldif\Rules\ValueSpecRuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ControlRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $valueSpecRule = null;

    public function getValueSpecRule() : ValueSpecRuleInterface
    {
        return $this->valueSpecRule;
    }
}

// vim: syntax=php sw=4 ts=4 et:
