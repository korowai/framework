<?php

class AssertExtendsClassTest extends \Korowai\Testing\TestCase
{
    public function testAssertExtendsClass()
    {
        $this->assertExtendsClass(\Exception::class, \RuntimeException::class);
        $this->assertExtendsClass(\Exception::class, new \RuntimeException);
    }

    public function testAssertExtendsClassFailure()
    {
        $this->assertExtendsClass(\Exception::class, self::class);
    }
}
