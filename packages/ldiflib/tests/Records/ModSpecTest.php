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
            'w/o attrValSpecs' => [
                'args' => [
                    'add',
                    'cn',
                ],
                'expect' => [
                    'modType' => 'add',
                    'attribute' => 'cn',
                    'attrValSpecs' => []
                ]
            ]
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "add", "cn", ['attrVal1']);

        $this->assertSame($snippet, $record->getSnippet());
        $this->assertModSpecHas($expect, $record);
    }

    public function test__setModType()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "add", "cn", ['attrVal1']);

        $this->assertSame($record, $record->setModType(['attrVal1']));
        $this->assertSame(['attrVal1'], $record->getModType());
    }

    public function test__setAttrValSpecs()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "dc=example,dc=org", []);

        $this->assertSame($record, $record->setAttrValSpecs(['attrVal1']));
        $this->assertSame(['attrVal1'], $record->getAttrValSpecs());
    }

    public function test__setAttrValSpecs()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)
                        ->getMockForAbstractClass();

        $record = new ModSpec($snippet, "dc=example,dc=org", []);

        $this->assertSame($record, $record->setAttrValSpecs(['attrVal1']));
        $this->assertSame(['attrVal1'], $record->getAttrValSpecs());
    }
}

// vim: syntax=php sw=4 ts=4 et:
