<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use Korowai\Testing\TestCase;
use Korowai\Testing\ActualProperties;
use Korowai\Testing\RecursivePropertiesSelector;
use Korowai\Testing\RecursivePropertiesUnwrapper;
use Korowai\Testing\RecursivePropertiesSelectorInterface;
use Korowai\Testing\ExpectedProperties;
use Korowai\Testing\PropertiesInterface;
use Korowai\Testing\ObjectPropertySelector;
use Korowai\Testing\ClassPropertySelector;
use Korowai\Testing\CircularDependencyException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\RecursivePropertiesSelector
 */
final class RecursivePropertiesSelectorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__RecursivePropertiesSelectorInterface() : void
    {
        $this->assertImplementsInterface(RecursivePropertiesSelectorInterface::class, RecursivePropertiesSelector::class);
    }

    //
    // unwrap()
    //

    public static function prov__selectProperties() : array
    {
        $objectSelector = new ObjectPropertySelector;
        $classSelector = new ClassPropertySelector;
        return [
            // #0
            [
                'selector' => new ExpectedProperties($objectSelector, []),
                'subject'  => new class { },
                'expect'   => [],
            ],

            // #1
            [
                'selector' => new ExpectedProperties($objectSelector, [
                    'foo'  => 'e:FOO',
                ]),
                'subject'  => new class {
                    public $foo = 'a:FOO';
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                ],
            ],

            // #2
            [
                'selector' => new ExpectedProperties($classSelector, [
                    'foo'  => 'e:FOO',
                ]),
                'subject'  => get_class(new class {
                    public static $foo = 'a:FOO';
                }),
                'expect'   => [
                    'foo'  => 'a:FOO',
                ],
            ],
        ];
    }


    /**
     * @dataProvider prov__selectProperties
     */
    public function test__selectProperties(ExpectedProperties $selector, $subject, array $expect) : void
    {
        $selector = new RecursivePropertiesSelector($selector);
        $unwrapper = new RecursivePropertiesUnwrapper;
        $selected = $selector->selectProperties($subject);
        $this->assertInstanceOf(ActualProperties::class, $selected);
        $this->assertSame($expect, $unwrapper->unwrap($selected));
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
