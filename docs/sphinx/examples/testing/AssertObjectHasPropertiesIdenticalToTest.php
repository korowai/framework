<?php

/**
 * @coversNothing
 */
final class AssertObjectHasPropertiesIdenticalToTest extends \Korowai\Testing\TestCase
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
        $this->assertObjectHasPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'getValue()'    => 'V'    // - value is 'V' (ok)
        ], $this);
    }

    public function testFailure()
    {
        // assert that:
        $this->assertObjectHasPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'getValue()'    => 'X'    // - value is 'X' (fail)
        ], $this);
    }

    public function testFailureWithGetter()
    {
        // assert that:
        $this->assertObjectHasPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'value'         => 'X'    // - value is 'X' (fail)
        ], $this, '', function (object $object) {
            return ($object instanceof AssertObjectHasPropertiesIdenticalToTest) ? ['value' => 'getValue'] : [];
        });
    }
}
