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
use Korowai\Lib\Ldif\Rules\ModSpecInitRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\AttrValSpecRuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ModSpecRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $modSpecInitRule = null;
    public $sepRule = null;
    public $attrValSpecRule = null;

    public function getModSpecInitRule() : ModSpecInitRuleInterface
    {
        return $this->modSpecInitRule;
    }

    public function getSepRule() : SepRuleInterface
    {
        return $this->sepRule;
    }

    public function getAttrValSpecRule() : AttrValSpecRuleInterface
    {
        return $this->attrValSpecRule;
    }
}

// vim: syntax=php sw=4 ts=4 et:
