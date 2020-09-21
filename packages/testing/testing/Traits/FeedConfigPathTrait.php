<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Traits;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait FeedConfigPathTrait
{
    /**
     * This method shall be used to implement dataProviders for tests that test
     * ``Korowai\<Package>\config_path()`` functions.
     *
     * Example usage
     *
     * ```
     *  final class ConfigPathTest extends \PHPUnit\Framework\TestCase
     *  {
     *      use \Korowai\Testing\Traits\FeedConfigPathTrait;
     *      public static function prov__data_path() : array
     *      {
     *          return feedConfigPath(realpath(__dir__.'/../../config'));
     *      }
     *
     *      /// @dataProvider prov__data_path
     *      public function test__data_path(array $args, string $expect) : void
     *      {
     *          $this->assertSame($expect, config_path(...$args));
     *      }
     *  }
     * ```
     *
     * @param string $configPath
     */
    public static function feedConfigPath(string $configPath) : array
    {
        return [
            [[], $configPath],
            [[''], $configPath],
            [['foo.php'], $configPath.'/foo.php'],
            [['/foo.php'], $configPath.'/foo.php'],
        ];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: