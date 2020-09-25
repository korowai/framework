<?php

/**
 * @coversNothing
 */
final class ObjectPropertiesIdenticalToTest extends \Korowai\Testing\TestCase
{
    public $attribute = 123;

    public function getValue()
    {
        return 321;
    }

    public function testSuccess()
    {
        // assert that:
        $this->assertThat($this, $this->objectPropertiesIdenticalTo([
            'attribute'     => 123,   // - $this->attribute is 123 (ok)
            'getValue()'    => 321    // - $this->getValue() is 321 (ok)
        ]));
    }

    public function testFailure()
    {
        // assert that:
        $this->assertThat($this, $this->objectPropertiesIdenticalTo([
            'attribute'     => '123',   // - $this->attribute is 123, not '123' (fail)
            'getValue()'    => null     // - $this->getValue() is 321, not null (fail)
        ]));
    }
}
