<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib\Illuminate;

use DI\ContainerBuilder;
use function Korowai\Ldaplib\config_path;

/**
 * Abstract base class for integration testing of korowai/ldiflib with Illuminate.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\Illuminate\TestCase
{
    use \Korowai\Testing\Ldaplib\ExamineLdaplibContainerTrait;

    public function getContainerConfigPath() : string
    {
        return config_path('illuminate/services.php');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
