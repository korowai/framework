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

use Korowai\Testing\Ldaplib\Illuminate\TestCase;
use function Korowai\Ldaplib\config_path;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversNothing
 */
final class IlluminateIntegrationTest extends TestCase
{
    public function test__illuminate_configures_container_correctly() : void
    {
        $this->examineLdaplibContainer($this->getContainer());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
