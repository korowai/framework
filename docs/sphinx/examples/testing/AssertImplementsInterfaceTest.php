<?php

class AssertImplementsInterfaceTest extends \Korowai\Testing\TestCase
{
    public function testAssertImplementsInterface()
    {
        $this->assertImplementsInterface(\Throwable::class, \RuntimeException::class);
        $this->assertImplementsInterface(\Throwable::class, new \RuntimeException);
    }

    public function testAssertImplementsInterfaceFailure()
    {
        $this->assertImplementsInterface(\Throwable::class, self::class);
    }
}
