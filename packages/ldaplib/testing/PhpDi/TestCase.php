<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib\PhpDi;

use DI\ContainerBuilder;
use function Korowai\Ldaplib\config_path;

/**
 * Abstract base class for integration testing of korowai/ldiflib with PhpDi.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\PhpDi\TestCase
{
    use \Korowai\Testing\Ldaplib\ExamineLdaplibContainerTrait;

    protected function setupContainer(ContainerBuilder $container) : void
    {
        $container->addDefinitions(config_path('php-di/services.php'));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
