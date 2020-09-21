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

use Korowai\Lib\Ldif\Nodes\ModSpec;
use Korowai\Lib\Ldif\Nodes\ModSpecInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Lib\Ldif\InvalidModTypeException;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\ModSpec
 */
final class ModSpecTest extends TestCase
{
    public function test__implements__ModSpecInterface() : void
    {
        $this->assertImplementsInterface(ModSpecInterface::class, ModSpec::class);
    }

    public function test__uses__HasAttrValSpecs() : void
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
                    'getModType()' => 'delete',
                    'getAttribute()' => 'cn',
                ]
            ],
            'ModSpec("add", "cn", [])' => [
                'args' => [
                    'add',
                    'cn',
                    [ 'attrVal0' ]
                ],
                'expect' => [
                    'getModType()' => 'add',
                    'getAttribute()' => 'cn',
                    'getAttrValSpecs()' => [ 'attrVal0' ]
                ]
            ],
            'ModSpec("replace", "cn", [])' => [
                'args' => [
                    'replace',
                    'cn',
                    [ 'attrVal0' ]
                ],
                'expect' => [
                    'getModType()' => 'replace',
                    'getAttribute()' => 'cn',
                    'getAttrValSpecs()' => [ 'attrVal0' ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect) : void
    {
        $record = new ModSpec(...$args);
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
    public function test__setModType(string $modType) : void
    {
        $record = new ModSpec("add", "cn");

        $this->assertSame($record, $record->setModType($modType));
        $this->assertSame($modType, $record->getModType());
    }

    public function test__setModType__withInvalidArg() : void
    {
        $record = new ModSpec("add", "cn");

        $message = 'Argument 1 to '.ModSpec::class.'::setModType() must be one of "add", "delete", or "replace", "foo" given.';
        $this->expectException(InvalidModTypeException::class);
        $this->expectExceptionMessage($message);

        $record->setModType("foo");
    }


    public function test__setAttribute() : void
    {
        $record = new ModSpec("add", "cn");

        $this->assertSame($record, $record->setAttribute("objectclass"));
        $this->assertSame("objectclass", $record->getAttribute());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
