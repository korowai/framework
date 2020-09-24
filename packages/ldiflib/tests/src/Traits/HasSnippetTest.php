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
use Korowai\Lib\Ldif\Traits\HasSnippet;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Traits\HasSnippet
 *
 * @internal
 */
final class HasSnippetTest extends TestCase
{
    public function testSnippet(): void
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
            ->getMockForAbstractClass()
        ;

        $object = $this->getMockBuilder(HasSnippet::class)
            ->getMockForTrait()
        ;

        $this->assertNull($object->getSnippet());

        $this->assertSame($object, $object->setSnippet($snippet));
        $this->assertSame($snippet, $object->getSnippet());

        $this->assertSame($object, $object->setSnippet(null));
        $this->assertNull($object->getSnippet());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
