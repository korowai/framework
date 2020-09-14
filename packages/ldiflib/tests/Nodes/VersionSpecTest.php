<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\VersionSpec;
use Korowai\Lib\Ldif\Nodes\VersionSpecInterface;
use Korowai\Lib\Ldif\SnippetInterface;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\VersionSpec
 */
final class VersionSpecTest extends TestCase
{
    public function test__implements__VersionSpecInterface()
    {
        $this->assertImplementsInterface(VersionSpecInterface::class, VersionSpec::class);
    }

    public function test__construct()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new VersionSpec($snippet, 123);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertSame(123, $record->getVersion());
    }

    public function test__setVersion()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new VersionSpec($snippet, 0);

        $this->assertSame($record, $record->setVersion(123));
        $this->assertSame(123, $record->getVersion());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
