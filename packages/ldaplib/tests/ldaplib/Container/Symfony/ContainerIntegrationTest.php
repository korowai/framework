<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Ldaplib\Container\Symfony;

use Korowai\Testing\Container\Symfony\TestCase;
use Korowai\Testing\Ldaplib\Container\ExamineConfiguredContainerTrait;
use function Korowai\Ldaplib\config_path;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Ldaplib\Container\ExamineConfiguredContainerTrait
 */
final class ContainerIntegrationTest extends TestCase
{
    use ExamineConfiguredContainerTrait;

    public function provideContainerConfigs() : array
    {
        return [ config_path('symfony/services.php') ];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
