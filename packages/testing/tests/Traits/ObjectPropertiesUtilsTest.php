<?php
/**
 * @file tests/Traits/ObjectPropertiesUtilsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Traits\ObjectPropertiesUtils;
use Korowai\Testing\Assertions\ObjectPropertiesAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ObjectPropertiesUtilsTest extends TestCase
{
    use ObjectPropertiesUtils;

    private $foo;

    public function getFoo()
    {
        return $this->foo;
    }

    public $qux; // not in getters map.

    // Required by trait
    public static function classPropertyGettersMap() : array
    {
        return [
            parent::class => [
                'bar' => 'getBar'
            ],
            ObjectPropertiesUtils::class => [
                'baz' => 'getBaz'
            ],
            self::class => [
                'foo' => 'getFoo'
            ],
        ];
    }

    public function test__getObjectPropertyGetters()
    {
        $this->assertSame([
            'bar' => 'getBar',
            'baz' => 'getBaz',
            'foo' => 'getFoo'
        ], self::getObjectPropertyGetters(self::class));
        $this->assertSame(['bar' => 'getBar'], self::getObjectPropertyGetters(parent::class));
        $this->assertSame(['baz' => 'getBaz'], self::getObjectPropertyGetters(ObjectPropertiesUtils::class));
    }

    public function test__getObjectProperty()
    {
        $this->foo = 'FOO';
        $this->qux = 'QUX';

        $this->assertSame('FOO', self::getObjectProperty($this, 'foo'));
        $this->assertSame('FOO', self::getObjectProperty($this, 'getFoo()'));
        $this->assertSame('QUX', self::getObjectProperty($this, 'qux'));
    }
}

// vim: syntax=php sw=4 ts=4 et:
