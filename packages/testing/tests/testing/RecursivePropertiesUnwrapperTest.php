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
use Korowai\Testing\RecursivePropertiesUnwrapper;
use Korowai\Testing\RecursivePropertiesUnwrapperInterface;
use Korowai\Testing\ExpectedProperties;
use Korowai\Testing\PropertiesInterface;
use Korowai\Testing\CircularDependencyException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\RecursivePropertiesUnwrapper
 */
final class RecursivePropertiesUnwrapperTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__RecursivePropertiesUnwrapperInterface() : void
    {
        $this->assertImplementsInterface(
            RecursivePropertiesUnwrapperInterface::class,
            RecursivePropertiesUnwrapper::class
        );
    }

    //
    // unwrap()
    //

    public static function prov__unwrap() : array
    {
        $actualProperties['[baz => BAZ]'] = new ActualProperties(['baz' => 'BAZ']);
        $expectProperties['[baz => BAZ]'] = new ExpectedProperties(['baz' => 'BAZ']);
        $arrayObject['[baz => BAZ]'] = new \ArrayObject(['baz' => 'BAZ']);

        return [
            // #0
            [
                'properties' => new ExpectedProperties([
                ]),
                'expect'     => [
                ],
            ],

            // #1
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO'
                ]),
                'expect'     => [
                    'foo' => 'FOO'
                ],
            ],

            // #2
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                ],
            ],

            // #3
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => new ExpectedProperties([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                    ],
                ],
            ],

            // #4
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => new ExpectedProperties([
                        'qux' => new ExpectedProperties(['baz' => 'BAZ']),
                        new ExpectedProperties(['fred' => 'FRED']),
                    ]),
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => [
                        'qux' => ['baz' => 'BAZ'],
                        ['fred' => 'FRED'],
                    ],
                ],
            ],

            // #5
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $actualProperties['[baz => BAZ]'],
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => $actualProperties['[baz => BAZ]'],
                ],
            ],

            // #6
            [
                'properties' => new ActualProperties([
                    'foo' => 'FOO',
                    'bar' => $expectProperties['[baz => BAZ]'],
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => $expectProperties['[baz => BAZ]'],
                ],
            ],

            // #7
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ],
            ],

            // #8
            [
                'properties' => new ExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect'     => [
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ],
            ]
        ];
    }


    /**
     * @dataProvider prov__unwrap
     */
    public function test__unwrap(PropertiesInterface $properties, array $expect) : void
    {
        $unwrapper = new RecursivePropertiesUnwrapper;
        $this->assertSame($expect, $unwrapper->unwrap($properties));
    }


    public static function prov__unwrap__throwsExceptionOnCircularDependency() : array
    {
        // #0
        $properties['#0'] = new ActualProperties([
            'foo' => [
            ],
        ]);
        $properties['#0']['foo']['bar'] = $properties['#0'];


        // #1
        $properties['#1'] = new ActualProperties([
            'foo' => [
                'bar' => [
                ],
            ],
        ]);
        $properties['#1']['foo']['bar']['baz'] = $properties['#1'];


        // #2
        $properties['#2'] = new ActualProperties([
            'foo' => [
                'bar' => new ActualProperties([
                    'baz' => 'BAZ'
                ]),
            ],
        ]);
        $properties['#2']['foo']['bar']['qux'] = $properties['#2']['foo']['bar'];

        // #3
        $properties['#3'] = new ActualProperties([
            'foo' => [
                'bar' => new ActualProperties([]),
                'baz' => new ActualProperties([]),
            ],
        ]);
        $properties['#3']['foo']['bar']['qux']  = $properties['#3']['foo']['baz'];
        $properties['#3']['foo']['baz']['fred'] = $properties['#3']['foo']['bar'];

        return [
            // #0
            [
                'properties' => $properties['#0'],
                'key'        => 'bar',
            ],

            // #1
            [
                'properties' => $properties['#1'],
                'key'        => 'baz',
            ],

            // #2
            [
                'properties' => $properties['#2'],
                'key'        => 'qux',
            ],

            // #3
            [
                'properties' => $properties['#3'],
                'key'        => 'fred',
            ],
        ];
    }

    /**
     * @dataProvider prov__unwrap__throwsExceptionOnCircularDependency
     */
    public function test__unwrap__throwsExceptionOnCircularDependency(PropertiesInterface $properties, $key) : void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;
        $id = preg_quote($id, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested properties at key $id\\.$/");

        (new RecursivePropertiesUnwrapper)->unwrap($properties);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
