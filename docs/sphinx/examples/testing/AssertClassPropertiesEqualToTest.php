<?php

/**
 * @coversNothing
 */
final class AssertClassPropertiesEqualToTest extends \Korowai\Testing\TestCase
{
    public static $attribute = 123;

    public static function getValue()
    {
        return 321;
    }

    public function testSuccess()
    {
        // assert that:
        $this->assertClassPropertiesEqualTo([
            'attribute'     => '123',     // - self::$attribute equals '123' (ok)
            'getValue()'    => '321',     // - self::getValue() equals '321' (ok)
        ], self::class);
    }

    public function testFailure()
    {
        // assert that:
        $this->assertClassPropertiesEqualTo([
            'attribute'     => '123',   // - self::$attribute equals '123' (ok),
            'getValue()'    => null,    // - self::$getValue() is 321, not equals null (fail)
        ], self::class);
    }
}
