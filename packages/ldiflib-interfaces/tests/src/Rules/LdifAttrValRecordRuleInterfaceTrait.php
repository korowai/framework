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
use Korowai\Lib\Ldif\Rules\AttrValSpecRuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifAttrValRecordRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $dnSpecRule = null;
    public $sepRule = null;
    public $attrValSpecRule = null;

    public function getDnSpecRule() : DnSpecRuleInterface
    {
        return $this->dnSpecRule;
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

// vim: syntax=php sw=4 ts=4 et tw=119: