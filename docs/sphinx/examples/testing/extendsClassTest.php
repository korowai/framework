<?php

class ExtendsClassTest extends \Korowai\Testing\TestCase
{
    public function testExtendsClass()
    {
        $this->assertThat(\RuntimeException::class, $this->extendsClass(\Exception::class));
        $this->assertThat(new \RuntimeException, $this->extendsClass(\Exception::class));
    }

    public function testExtendsClassFailure()
    {
        $this->assertThat(self::class, $this->extendsClass(\Exception::class));
    }
}
