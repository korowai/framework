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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Traits\FeedConfigPathTrait;

use function Korowai\Ldaplib\config_path;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Ldaplib\config_path
 */
final class FunctionsTest extends TestCase
{
    use FeedConfigPathTrait;
    public static function prov__config_path() : array
    {
        return self::feedConfigPath(realpath(__dir__.'/../../config'));
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
