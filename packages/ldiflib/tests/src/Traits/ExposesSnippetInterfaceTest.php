<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesSnippetInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Traits\ExposesSnippetInterface
 *
 * @internal
 */
final class ExposesSnippetInterfaceTest extends TestCase
{
    public function getTestObject(SnippetInterface $snippet = null)
    {
        $obj = new class($snippet) implements SnippetInterface {
            use ExposesSnippetInterface;

            public function __construct(?SnippetInterface $snippet)
            {
                $this->snippet = $snippet;
            }

            public function getSnippet(): ?SnippetInterface
            {
                return $this->snippet;
            }
        };

        return $obj;
    }

    public function testUsesExposesLocationInterface(): void
    {
        $this->assertUsesTrait(ExposesLocationInterface::class, ExposesSnippetInterface::class);
    }

    public function testGetLocation(): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $obj = $this->getTestObject($snippet);
        $this->assertSame($snippet, $obj->getLocation());
    }

    public function testGetLength(): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $snippet->expects($this->once())
            ->method('getLength')
            ->willReturn(17)
        ;
        $obj = $this->getTestObject($snippet);

        $this->assertSame(17, $obj->getLength());
    }

    public function testGetEndOffset(): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $snippet->expects($this->once())
            ->method('getEndOffset')
            ->willReturn(17)
        ;
        $obj = $this->getTestObject($snippet);

        $this->assertSame(17, $obj->getEndOffset());
    }

    public function testGetSourceLength(): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $snippet->expects($this->once())
            ->method('getSourceLength')
            ->willReturn(17)
        ;
        $obj = $this->getTestObject($snippet);

        $this->assertSame(17, $obj->getSourceLength());
    }

    public function testGetSourceEndOffset(): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $snippet->expects($this->once())
            ->method('getSourceEndOffset')
            ->willReturn(17)
        ;
        $obj = $this->getTestObject($snippet);

        $this->assertSame(17, $obj->getSourceEndOffset());
    }

    public function encodingCases()
    {
        return [[], ['U']];
    }

    public function testGetSourceCharLength(...$enc): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $snippet->expects($this->once())
            ->method('getSourceCharLength')
            ->with(...$enc)
            ->willReturn(17)
        ;
        $obj = $this->getTestObject($snippet);

        $this->assertSame(17, $obj->getSourceCharLength(...$enc));
    }

    public function testGetSourceCharEndOffset(...$enc): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;
        $snippet->expects($this->once())
            ->method('getSourceCharEndOffset')
            ->with(...$enc)
            ->willReturn(17)
        ;
        $obj = $this->getTestObject($snippet);

        $this->assertSame(17, $obj->getSourceCharEndOffset(...$enc));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
