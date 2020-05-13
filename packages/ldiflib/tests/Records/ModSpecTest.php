<?php
/**
 * @file tests/Records/ModSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\ModSpec;
use Korowai\Lib\Ldif\Records\ModSpecInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Lib\Ldif\Exception\InvalidModTypeException;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModSpecTest extends TestCase
{
    public function test__implements__ModSpecInterface()
    {
        $this->assertImplementsInterface(ModSpecInterface::class, ModSpec::class);
    }

    public function test__uses__DecoratesSnippetInterface()
    {
        $this->assertUsesTrait(DecoratesSnippetInterface::class, ModSpec::class);
    }

    public function test__uses__HasAttrValSpecs()
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, ModSpec::class);
    }

    public static function construct__cases()
    {
        return [
            'ModSpec("delete", "cn")' => [
                'args' => [
                    'delete',
                    'cn'
                ],
                'expect' => [
                    'modType' => 'delete',
                    'attribute' => 'cn',
                ]
            ],
            'ModSpec("add", "cn", [])' => [
                'args' => [
                    'add',
                    'cn',
                    [ 'attrVal0' ]
                ],
                'expect' => [
                    'modType' => 'add',
                    'attribute' => 'cn',
                    'attrValSpecs' => [ 'attrVal0' ]
                ]
            ],
            'ModSpec("replace", "cn", [])' => [
                'args' => [
                    'replace',
                    'cn',
                    [ 'attrVal0' ]
                ],
                'expect' => [
                    'modType' => 'replace',
                    'attribute' => 'cn',
                    'attrValSpecs' => [ 'attrVal0' ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, ...$args);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public function modType__cases()
    {
        return [
            ['add'],
            ['delete'],
            ['replace']
        ];
    }

    /**
     * @dataProvider modType__cases
     */
    public function test__setModType(string $modType)
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "add", "cn");

        $this->assertSame($record, $record->setModType($modType));
        $this->assertSame($modType, $record->getModType());
    }

    public function test__setModType__withInvalidArg()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "add", "cn");

        $message = 'Argument 1 to '.ModSpec::class.'::setModType() must be one of "add", "delete", or "replace", "foo" given.';
        $this->expectException(InvalidModTypeException::class);
        $this->expectExceptionMessage($message);

        $record->setModType("foo");
    }


    public function test__setAttribute()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "add", "cn");

        $this->assertSame($record, $record->setAttribute("objectclass"));
        $this->assertSame("objectclass", $record->getAttribute());
    }
}

// vim: syntax=php sw=4 ts=4 et:
