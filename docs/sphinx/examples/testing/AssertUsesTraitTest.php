<?php

trait ExampleTrait
{
}

class AssertUsesTraitTest extends \Korowai\Testing\TestCase
{
    use ExampleTrait;

    public function testAssertUsesTrait()
    {
        $this->assertUsesTrait(ExampleTrait::class, self::class);
        $this->assertUsesTrait(ExampleTrait::class, $this);
    }

    public function testAssertUsesTraitFailure()
    {
        $this->assertUsesTrait(ExampleTrait::class, \RuntimeException::class);
    }
}
