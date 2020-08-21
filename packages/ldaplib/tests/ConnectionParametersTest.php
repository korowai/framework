<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldap\ConnectionParameters;
use Korowai\Lib\Ldap\ConnectionParametersInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ConnectionParametersTest extends TestCase
{
    public function test__implements__ConnectionParametersInterface()
    {
        $this->assertImplementsInterface(ConnectionParametersInterface::class, ConnectionParameters::class);
    }
    // FIXME: implement tests
}

// vim: syntax=php sw=4 ts=4 et:
