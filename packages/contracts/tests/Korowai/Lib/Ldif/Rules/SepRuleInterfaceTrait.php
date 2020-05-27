<?php
/**
 * @file tests/Korowai/Lib/Ldif/Rules/SepRuleInterfaceTrait.php
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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SepRuleInterfaceTrait
{
    use RuleInterfaceTrait;

    public function parse(
        ParserStateInterface $state,
        string &$value = null,
        bool $trying = false
    ) : bool {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
