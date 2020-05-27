<?php
/**
 * @file tests/Korowai/Lib/Ldif/Rules/ControlRuleInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Tests\Lib\Ldif\RuleInterfaceTrait;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\Nodes\ControlInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ControlRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public function parse(
        ParserStateInterface $state,
        ControlInterface &$value = null,
        bool $trying = false
    ) : bool {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
