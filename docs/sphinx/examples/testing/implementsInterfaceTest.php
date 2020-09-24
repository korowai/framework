<?php

/**
 * @coversNothing
 */
final class ImplementsInterfaceTest extends \Korowai\Testing\TestCase
{
    public function testImplementsInterface()
    {
        $this->assertThat(\RuntimeException::class, $this->implementsInterface(\Throwable::class));
        $this->assertThat(new \RuntimeException, $this->implementsInterface(\Throwable::class));
    }

    public function testImplementsInterfaceFailure()
    {
        $this->assertThat(self::class, $this->implementsInterface(\Throwable::class));
    }
}
