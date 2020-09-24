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
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\Exporter;
use Korowai\Testing\Properties\PropertiesInterface;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\TestCase;
use SebastianBergmann\Exporter\Exporter as SebastianBergmannExporter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\Exporter
 *
 * @internal
 */
final class ExporterTest extends TestCase
{
    public function createActualProperties(...$args): ActualProperties
    {
        return new ActualProperties(...$args);
    }

    public function createExpectedProperties(...$args): ExpectedProperties
    {
        $selector = $this->createMock(PropertySelectorInterface::class);
        return new ExpectedProperties($selector, ...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function testExtendsSebastianBergmannExporter(): void
    {
        $this->assertExtendsClass(SebastianBergmannExporter::class, Exporter::class);
    }

    //
    // describe()
    //

    public function provDescribe(): array
    {
        return [
            'ExpectedPropertiesInterface' => [
                'argument' => $this->createMock(ExpectedPropertiesInterface::class),
                'expected' => 'Properties <Expect>',
            ],

            'ActualPropertiesInterface' => [
                'argument' => $this->createMock(ActualPropertiesInterface::class),
                'expected' => 'Properties <Actual>',
            ],

            'PropertiesInterface' => [
                'argument' => $this->createMock(PropertiesInterface::class),
                'expected' => 'Properties',
            ],
        ];
    }

    /**
     * @dataProvider provDescribe
     */
    public function testDescribe(PropertiesInterface $argument, string $expected): void
    {
        $exporter = new Exporter();
        $this->assertSame($expected, $exporter->describe($argument));
    }

    //
    // export()
    //

    public function provExport(): array
    {
        $sebastianExporter = new SebastianBergmannExporter();
        $sebastianHandles = [
            null,               // #0
            'abc',              // #1
            123,                // #2
            [                   // #3
                'foo' => 'FOO',
            ]
        ];

        $cases = [];
        foreach ($sebastianHandles as $value) {
            $cases[] = [
                'argument' => $value,
                'expected' => $sebastianExporter->export($value),
            ];
        }

        // #4
        $cases[] = [
            'arguments' => $this->createActualProperties([]),
            'expected' => "Properties <Actual> ()",
        ];

        // #5
        $cases[] = [
            'arguments' => $this->createExpectedProperties([]),
            'expected' => "Properties <Expect> ()",
        ];

        // #6
        $cases[] = [
            'arguments' => $this->createActualProperties([
                'foo' => 'FOO',
            ]),
            'expected' => "Properties <Actual> (\n".
                          "    'foo' => 'FOO'\n".
                          ")",
        ];

        // #7
        $cases[] = [
            'arguments' => $this->createExpectedProperties([
                'foo' => 'FOO',
            ]),
            'expected' => "Properties <Expect> (\n".
                          "    'foo' => 'FOO'\n".
                          ")",
        ];


        return $cases;
    }

    /**
     * @dataProvider provExport
     */
    public function testExport($argument, string $expected): void
    {
        $exporter = new Exporter();
        $this->assertSame($expected, $exporter->export($argument));
    }

    public function testExportHandlesCycle(): void
    {
        $exporter = new Exporter();
        $argument = $this->createActualProperties([]);
        $argument['foo'] = $argument;

        $expected =
            "Properties <Actual> (\n".
            "    'foo' => Properties <Actual>\n".
            ")";
        $this->assertSame($expected, $exporter->export($argument));
    }

    //
    // shortenedExport()
    //

    public function provShortenedExport(): array
    {
        $sebastianExporter = new SebastianBergmannExporter();
        $sebastianHandles = [
            null,               // #0
            'abc',              // #1
            123,                // #2
            new \StdClass(),    // #3
        ];

        $cases = [];
        foreach ($sebastianHandles as $value) {
            $cases[] = [
                'argument' => $value,
                'expected' => $sebastianExporter->shortenedExport($value),
            ];
        }

        // #4
        $cases[] = [
            'arguments' => $this->createActualProperties([]),
            'expected' => "Properties <Actual> ()",
        ];

        // #5
        $cases[] = [
            'arguments' => $this->createExpectedProperties([]),
            'expected' => "Properties <Expect> ()",
        ];

        // #6
        $cases[] = [
            'arguments' => $this->createActualProperties([
                'foo' => 'FOO',
            ]),
            'expected' => "Properties <Actual> (...)",
        ];

        // #7
        $cases[] = [
            'arguments' => $this->createExpectedProperties([
                'foo' => 'FOO',
            ]),
            'expected' => "Properties <Expect> (...)",
        ];


        return $cases;
    }

    /**
     * @dataProvider provShortenedExport
     */
    public function testShortenedExport($argument, string $expected): void
    {
        $exporter = new Exporter();
        $this->assertSame($expected, $exporter->shortenedExport($argument));
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
