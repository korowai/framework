<?php

/**
 * @coversNothing
 */
final class ClassPropertiesEqualToTest extends \Korowai\Testing\TestCase
{
    public static $attribute = 123;

    public static function getValue()
    {
        return 321;
    }

    public function testSuccess()
    {
        // assert that:
        $this->assertThat(self::class, $this->classPropertiesEqualTo([
            'attribute'     => '123', // - self::$attribute is 123, equals '123' (ok)
            'getValue()'    => 321    // - self::getValue() is 321 (ok)
        ]));
    }

    public function testFailure()
    {
        // assert that:
        $this->assertThat(self::class, $this->classPropertiesEqualTo([
            'attribute'     => '123',   // - self::$attribute is 123, equals '123' (ok)
            'getValue()'    => null     // - self::getValue() is 321, not equals null (fail)
        ]));
    }
}
