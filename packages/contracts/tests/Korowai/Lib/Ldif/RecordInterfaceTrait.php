<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\RecordVisitorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait RecordInterfaceTrait
{
    public function getDn() : string
    {
        return "";
    }

    public function acceptRecordVisitor(RecordVisitorInterface $visitor)
    {
    }
}

// vim: syntax=php sw=4 ts=4 et:
