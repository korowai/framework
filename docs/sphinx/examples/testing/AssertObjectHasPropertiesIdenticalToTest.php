<?php

/**
 * @coversNothing
 */
final class AssertObjectPropertiesIdenticalToTest extends \Korowai\Testing\TestCase
{
    public $attribute;

    public function getValue()
    {
        return 'V';
    }

    public function setUp() : void
    {
        $this->attribute = 'A';
    }

    public function testSuccess()
    {
        // assert that:
        $this->assertObjectPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'getValue()'    => 'V'    // - value is 'V' (ok)
        ], $this);
    }

    public function testFailure()
    {
        // assert that:
        $this->assertObjectPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'getValue()'    => 'X'    // - value is 'X' (fail)
        ], $this);
    }

    public function testFailureWithGetter()
    {
        // assert that:
        $this->assertObjectPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'value'         => 'X'    // - value is 'X' (fail)
        ], $this, '', function (object $object) {
            return ($object instanceof AssertObjectPropertiesIdenticalToTest) ? ['value' => 'getValue'] : [];
        });
    }
}
