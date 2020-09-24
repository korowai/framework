<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Parser;
use Korowai\Lib\Ldif\ParserInterface;
use Korowai\Testing\Ldiflib\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Parser
 *
 * @internal
 */
final class ParserTest extends TestCase
{
//    protected static function getDefaultConfig()
//    {
//        return [
//            'file_type' => 'content',
//            'version_required' => false
//        ];
//    }
//
//    protected static function getSupportedFileTypes()
//    {
//        return ['content', 'changes', 'mixed', 'detect'];
//    }
//
    public function testImplementsParserInterface(): void
    {
        $this->assertImplementsInterface(ParserInterface::class, Parser::class);
    }

//
//    public function prov__construct()
//    {
//        return [
//            'without args' => [
//                'args' => [],
//                'expect' => []
//            ],
//            'with config' => [
//                'args' => [
//                    [
//                        'file_type' => 'changes',
//                        'version_required' => true
//                    ]
//                ],
//                'expect' => [
//                    'config' => [
//                        'file_type' => 'changes',
//                        'version_required' => true,
//                    ]
//                ]
//            ]
//        ];
//    }
//
//    /**
//     * @dataProvider prov__construct
//     */
//    public function test__construct(array $args, array $expect)
//    {
//        $parser = new Parser(...$args);
//
//        $expectedConfig = $expect['config'] ?? $this->getDefaultConfig();
//
//        $this->assertSame($expectedConfig, $parser->getConfig());
//    }
//
//    public function prov__config()
//    {
//        $cases = [
//            [
//                'config' => [],
//            ],
//            [
//                'config' => [
//                    'version_required' => false,
//                ],
//                'expect' => [
//                    'file_type' => 'content',
//                    'version_required' => false
//                ]
//            ],
//            [
//                'config' => [
//                    'version_required' => true,
//                ],
//                'expect' => [
//                    'file_type' => 'content',
//                    'version_required' => true
//                ]
//            ],
//        ];
//
//        foreach ($this->getSupportedFileTypes() as $fileType) {
//            $cases[] = [
//                'config' => [
//                    'file_type' => $fileType,
//                ],
//                'expect' => [
//                    'file_type' => $fileType,
//                    'version_required' => false
//                ]
//            ];
//        }
//
//        return $cases;
//    }
//
//    /**
//     * @dataProvider prov__config
//     */
//    public function test__configure(array $config, array $expect = null)
//    {
//        $parser = new Parser;
//
//        if ($expect === null) {
//            $expect = $this->getDefaultConfig();
//        }
//
//        $this->assertSame($parser, $parser->configure($config));
//        $this->assertSame($expect, $parser->getConfig());
//    }
//
//    public function test__configure__wrongFileType()
//    {
//        $parser = new Parser;
//
//        $this->expectException(InvalidOptionsException::class);
//        $this->expectExceptionMessage('The option "file_type" with value "foo" is invalid');
//
//        $parser->configure(['file_type' => 'foo']);
//    }
//
//    public function test__configure__wrongVersionRequired()
//    {
//        $parser = new Parser;
//
//        $this->expectException(InvalidOptionsException::class);
//        $this->expectExceptionMessage('The option "version_required" with value "foo" is expected to be of type "bool"');
//
//        $parser->configure(['version_required' => 'foo']);
//    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
