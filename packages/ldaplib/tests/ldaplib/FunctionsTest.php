<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\LdapLib;

use function Korowai\Ldaplib\config_path;
use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Traits\FeedConfigPathTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Ldaplib\config_path
 *
 * @internal
 */
final class FunctionsTest extends TestCase
{
    use FeedConfigPathTrait;

    public static function provConfigPath(): array
    {
        return self::feedConfigPath(realpath(__DIR__.'/../../config'));
    }

    /**
     * @dataProvider provConfigPath
     */
    public function testConfigPath(array $args, string $expect): void
    {
        $this->assertSame($expect, config_path(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
