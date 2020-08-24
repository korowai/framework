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
use Korowai\Lib\Ldif\Rules\LdifContentRuleInterface;
use Korowai\Lib\Ldif\Rules\LdifChangesRuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifFileRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public $ldifContentRule = null;
    public $ldifChangesRule = null;

    public function getLdifContentRule() : LdifContentRuleInterface
    {
        return $this->ldifContentRule;
    }

    public function getLdifChangesRule() : LdifChangesRuleInterface
    {
        return $this->ldifChangesRule;
    }
}

// vim: syntax=php sw=4 ts=4 et:
