<?php

class ObjectHasPropertiesIdenticalToTest extends \Korowai\Testing\TestCase
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
        $this->assertThat($this, $this->objectHasPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'getValue()'    => 'V'    // - value is 'V' (ok)
        ]));
    }

    public function testFailure()
    {
        // assert that:
        $this->assertThat($this, $this->objectHasPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'getValue()'    => 'X'    // - value is 'X' (fail)
        ]));
    }

    public function testFailureWithGetter()
    {
        // assert that:
        $this->assertThat($this, $this->objectHasPropertiesIdenticalTo([
            'attribute'     => 'A',   // - attribute is 'A' (ok)
            'value'         => 'X'    // - value is 'X' (fail)
        ], function (object $object) {
            return ($object instanceof ObjectHasPropertiesIdenticalToTest) ? ['value' => 'getValue'] : [];
        }));
    }
}
