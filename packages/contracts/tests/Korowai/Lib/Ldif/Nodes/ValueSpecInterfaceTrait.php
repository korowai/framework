<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/ValueSpecInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\NodeInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ValueSpecInterfaceTrait
{
    use NodeInterfaceTrait;

    public $type = null;
    public $spec = null;
    public $content = null;

    public function getType() : int
    {
        return $this->type;;
    }

    public function getSpec()
    {
        return $this->spec;
    }

    public function getContent() : string
    {
        return $this->content;
    }
}

// vim: syntax=php sw=4 ts=4 et:
