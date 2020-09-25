<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib\Container\Illuminate;

use function Korowai\Ldaplib\config_path;
use Korowai\Testing\Ldaplib\Container\ExamineConfiguredContainerTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Ldaplib\Container\ExamineConfiguredContainerTrait
 *
 * @internal
 */
final class ContainerIntegrationTest extends \Korowai\Testing\Container\Illuminate\TestCase
{
    use ExamineConfiguredContainerTrait;

    public function provideContainerConfigs(): array
    {
        return [config_path('illuminate/services.php')];
    }
}

// vim: syntax=php sw=4 ts=4 et:
