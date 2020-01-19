<?php

trait ExampleTrait
{
}

class UsesTraitTest extends \Korowai\Testing\TestCase
{
    use ExampleTrait;

    public function testUsesTrait()
    {
        $this->assertThat(self::class, $this->usesTrait(ExampleTrait::class));
        $this->assertThat($this, $this->usesTrait(ExampleTrait::class));
    }

    public function testUsesTraitFailure()
    {
        $this->assertThat(\RuntimeException::class, $this->usesTrait(ExampleTrait::class));
    }
}
