<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Properties;

use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\CircularDependencyException;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapper;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapperInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\RecursivePropertiesUnwrapper
 *
 * @internal
 */
final class RecursivePropertiesUnwrapperTest extends TestCase
{
    public const UNIQUE_TAG = RecursivePropertiesUnwrapper::UNIQUE_TAG;

    public function createExpectedProperties(...$args): ExpectedPropertiesInterface
    {
        $selector = $this->createMock(PropertySelectorInterface::class);

        return new ExpectedProperties($selector, ...$args);
    }

    public function createActualProperties(...$args): ActualPropertiesInterface
    {
        return new ActualProperties(...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursivePropertiesUnwrapperInterface(): void
    {
        $this->assertImplementsInterface(
            RecursivePropertiesUnwrapperInterface::class,
            RecursivePropertiesUnwrapper::class
        );
    }

    //
    // unwrap()
    //

    public function prov__unwrap(): array
    {
        $actualProperties['[baz => BAZ]'] = $this->createActualProperties(['baz' => 'BAZ']);
        $expectProperties['[baz => BAZ]'] = $this->createExpectedProperties(['baz' => 'BAZ']);
        $arrayObject['[baz => BAZ]'] = new \ArrayObject(['baz' => 'BAZ']);

        return [
            // #0
            [
                'properties' => $this->createExpectedProperties([
                ]),
                'expect' => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #1
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #2
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #3
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $this->createExpectedProperties([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #4
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $this->createExpectedProperties([
                        'qux' => $this->createExpectedProperties(['baz' => 'BAZ']),
                        $this->createExpectedProperties(['fred' => 'FRED']),
                    ]),
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'qux' => [
                            'baz' => 'BAZ',
                            self::UNIQUE_TAG => true,
                        ],
                        0 => [
                            'fred' => 'FRED',
                            self::UNIQUE_TAG => true,
                        ],
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #5
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $actualProperties['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => $actualProperties['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #6
            [
                'properties' => $this->createActualProperties([
                    'foo' => 'FOO',
                    'bar' => $expectProperties['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => $expectProperties['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #7
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #8
            [
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__unwrap
     */
    public function testUnwrap(PropertiesInterface $properties, array $expect): void
    {
        $unwrapper = new RecursivePropertiesUnwrapper();
        $this->assertSame($expect, $unwrapper->unwrap($properties));
    }

    public function prov__unwrap__throwsExceptionOnCircularDependency(): array
    {
        // #0
        $properties['#0'] = $this->createActualProperties([
            'foo' => [
            ],
        ]);
        $properties['#0']['foo']['bar'] = $properties['#0'];

        // #1
        $properties['#1'] = $this->createActualProperties([
            'foo' => [
                'bar' => [
                ],
            ],
        ]);
        $properties['#1']['foo']['bar']['baz'] = $properties['#1'];

        // #2
        $properties['#2'] = $this->createActualProperties([
            'foo' => [
                'bar' => $this->createActualProperties([
                    'baz' => 'BAZ',
                ]),
            ],
        ]);
        $properties['#2']['foo']['bar']['qux'] = $properties['#2']['foo']['bar'];

        // #3
        $properties['#3'] = $this->createActualProperties([
            'foo' => [
                'bar' => $this->createActualProperties([]),
                'baz' => $this->createActualProperties([]),
            ],
        ]);
        $properties['#3']['foo']['bar']['qux'] = $properties['#3']['foo']['baz'];
        $properties['#3']['foo']['baz']['fred'] = $properties['#3']['foo']['bar'];

        return [
            // #0
            [
                'properties' => $properties['#0'],
                'key' => 'bar',
            ],

            // #1
            [
                'properties' => $properties['#1'],
                'key' => 'baz',
            ],

            // #2
            [
                'properties' => $properties['#2'],
                'key' => 'qux',
            ],

            // #3
            [
                'properties' => $properties['#3'],
                'key' => 'fred',
            ],
        ];
    }

    /**
     * @dataProvider prov__unwrap__throwsExceptionOnCircularDependency
     *
     * @param mixed $key
     */
    public function testUnwrapThrowsExceptionOnCircularDependency(PropertiesInterface $properties, $key): void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;
        $id = preg_quote($id, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested properties at key {$id}\\.$/");

        (new RecursivePropertiesUnwrapper())->unwrap($properties);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
