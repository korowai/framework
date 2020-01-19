<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\NodeInterfaceTrait;
use Korowai\Tests\Lib\Ldif\RecordInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifAttrValRecordInterfaceTrait
{
    use RecordInterfaceTrait;
    use NodeInterfaceTrait;
    use HasAttrValSpecsInterfaceTrait;
}

// vim: syntax=php sw=4 ts=4 et:
