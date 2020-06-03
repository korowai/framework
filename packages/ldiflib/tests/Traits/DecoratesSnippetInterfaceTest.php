<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;
use Korowai\Lib\Ldif\Traits\ExposesSnippetInterface;
use Korowai\Lib\Ldif\Traits\HasSnippet;
use Korowai\Lib\Ldif\SnippetInterface;

use Korowai\Testing\Ldiflib\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DecoratesSnippetInterfaceTest extends TestCase
{
    public function test__uses__ExposesSnippetInterface()
    {
        $this->assertUsesTrait(ExposesSnippetInterface::class, DecoratesSnippetInterface::class);
    }

    public function test__uses__HasSnippet()
    {
        $this->assertUsesTrait(HasSnippet::class, DecoratesSnippetInterface::class);
    }

    public function test__snippet()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $object = $this->getMockBuilder(DecoratesSnippetInterface::class)
                       ->getMockForTrait();

        $this->assertNull($object->getSnippet());

        $this->assertSame($object, $object->setSnippet($snippet));
        $this->assertSame($snippet, $object->getSnippet());

        $this->assertSame($object, $object->setSnippet(null));
        $this->assertNull($object->getSnippet());
    }
}

// vim: syntax=php sw=4 ts=4 et:
