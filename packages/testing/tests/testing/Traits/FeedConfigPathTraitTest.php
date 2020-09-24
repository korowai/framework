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

use Korowai\Testing\Traits\FeedConfigPathTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Traits\FeedConfigPathTrait
 *
 * @internal
 */
final class FeedConfigPathTraitTest extends TestCase
{
    use FeedConfigPathTrait;

    public static function provFeedConfigPath(): array
    {
        return self::feedConfigPath(__DIR__);
    }

    /**
     * @dataProvider provFeedConfigPath
     */
    public function testFeedConfigPath(array $args, string $expect): void
    {
        $config_path = function (string $file = '') {
            return __DIR__.($file ? '/'.ltrim($file, '/') : '');
        };
        self::assertThat($args, self::logicalOr(self::countOf(0), self::countOf(1)));
        self::assertSame($expect, $config_path(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
