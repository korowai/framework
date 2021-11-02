<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Ldaplib;

use Korowai\Testing\Ldaplib\AbstractMockBuilder;
use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Ldaplib\AbstractMockBuilder
 *
 * @internal
 */
final class AbstractMockBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testGetTestCase(): void
    {
        $builder = $this->createAbstractMockBuilderMock();
        $this->assertInstanceOf(self::class, $builder->getTestCase());
    }

    public function testGetBuilder(): void
    {
        $builder = $this->createAbstractMockBuilderMock(\Exception::class);

        $this->assertInstanceOf(MockBuilder::class, $builder->getBuilder());
        $this->assertInstanceOf(\Exception::class, $builder->getBuilder()->getMock());
    }

    protected function createAbstractMockBuilderMock(string $mockedType = null)
    {
        $builder = $this->getMockBuilder(AbstractMockBuilder::class);
        if (null !== $mockedType) {
            $mock = $builder->disableOriginalConstructor()
                ->getMockForAbstractClass()
            ;
            $mock->expects($this->any())
                ->method('mockedType')
                ->willReturn($mockedType)
            ;
            $mock->__construct($this);
        } else {
            $mock = $builder->setConstructorArgs([$this])
                ->getMockForAbstractClass()
            ;
        }

        return $mock;
    }
}

// vim: syntax=php sw=4 ts=4 et:
