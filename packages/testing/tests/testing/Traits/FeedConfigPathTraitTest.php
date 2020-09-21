<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Traits\FeedConfigPathTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Traits\FeedConfigPathTrait
 */
final class FeedConfigPathTraitTest extends TestCase
{
    use FeedConfigPathTrait;

    public static function prov__feedConfigPath() : array
    {
        return self::feedConfigPath(__dir__);
    }

    /**
     * @dataProvider prov__feedConfigPath
     */
    public function test__feedConfigPath(array $args, string $expect) : void
    {
        $config_path = function (string $file = '') {
            return __dir__ . ($file ? '/'.ltrim($file, '/') : '');
        };
        self::assertThat($args, self::logicalOr(self::countOf(0), self::countOf(1)));
        self::assertSame($expect, $config_path(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
