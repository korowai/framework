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

use Korowai\Testing\Ldaplib\TestCase;

use function Korowai\Lib\Ldap\config_path;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\config_path
 */
final class ConfigFunctionsTest extends TestCase
{
    public static function prov__config_path() : array
    {
        $configPath = realpath(__dir__ . '/../config');
        return [
            [[], $configPath],
            [[''], $configPath],
            [['foo.php'], $configPath.'/foo.php'],
            [['/foo.php'], $configPath.'/foo.php'],
        ];
    }

    /**
     * @dataProvider prov__config_path
     */
    public function test__config_path(array $args, string $expect) : void
    {
        $this->assertSame($expect, config_path(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
