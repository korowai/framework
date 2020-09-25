<?php

/**
 * @coversNothing
 */
final class AssertClassPropertiesIdenticalToTest extends \Korowai\Testing\TestCase
{
    public static $attribute = 123;

    public static function getValue()
    {
        return 321;
    }

    public function testSuccess()
    {
        // assert that:
        $this->assertClassPropertiesIdenticalTo([
            'attribute'     => 123,     // - self::$attribute is 123 (ok)
            'getValue()'    => 321,     // - self::getValue() is 321 (ok)
        ], self::class);
    }

    public function testFailure()
    {
        // assert that:
        $this->assertClassPropertiesIdenticalTo([
            'attribute'     => '123',   // - self::$attribute is 123, not '123' (fail),
            'getValue()'    => null,    // - self::getValue() is 321, not null (fail)
        ], self::class);
    }
}
