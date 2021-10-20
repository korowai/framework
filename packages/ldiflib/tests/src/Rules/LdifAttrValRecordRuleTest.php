<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\LdifAttrValRecordRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\LdifAttrValRecordRule
 *
 * @internal
 */
final class LdifAttrValRecordRuleTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use ObjectPropertiesIdenticalToTrait;

    public function testExtendsAbstractLdifRecordRule(): void
    {
        $this->assertExtendsClass(AbstractLdifRecordRule::class, LdifAttrValRecordRule::class);
    }

    public static function provConstruct(): array
    {
        $dnSpecRule = new DnSpecRule();
        $sepRule = new SepRule();
        $attrValSpecRule = new AttrValSpecRule();

        return [
            '__construct()' => [
                'args' => [],
                'expect' => [
                ],
            ],
            '__construct([...])' => [
                'args' => [[
                    'dnSpecRule' => $dnSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecRule' => $attrValSpecRule,
                ]],
                'expect' => [
                    'getDnSpecRule()' => $dnSpecRule,
                    'getSepRule()' => $sepRule,
                    'getAttrValSpecRule()' => $attrValSpecRule,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = new LdifAttrValRecordRule(...$args);
        $this->assertInstanceOf(DnSpecRule::class, $rule->getDnSpecRule());
        $this->assertInstanceOf(SepRule::class, $rule->getSepRule());
        $this->assertInstanceOf(AttrValSpecRule::class, $rule->getAttrValSpecRule());
        $this->assertObjectPropertiesIdenticalTo($expect, $rule);
    }

    //
    // parse()
    //

    public static function provParse(): array
    {
        return [
            //0
            [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "dn:" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //1
            [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //2
            [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['foo: ', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "dn:" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //3
            [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 22,
                                'getMessage()' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //4
            [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\ndc", 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 22,
                                'getMessage()' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //5
            [
                //            000000000011111111112 22222 222233333
                //            012345678901234567890 12345 678901234
                'source' => ["dn: dc=example,dc=org\ndc: \n", 0],
                'args' => [true],
                'expect' => [
                    'init' => false,
                    'result' => true,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'dc',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => '',
                                    'getContent()' => '',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 27]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //6
            [
                //            000000000011111111112 222222222333 33
                //            012345678901234567890 123456789012 34
                'source' => ["dn: dc=example,dc=org\ndc: example\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => false,
                    'result' => true,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'dc',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'example',
                                    'getContent()' => 'example',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 34]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            //7
            [
                //            000000000011111111112 222222222333 33333334444444444555555 55
                //            012345678901234567890 123456789012 34567890123456789012345 67
                'source' => ["dn: dc=example,dc=org\ndc: example\ncomment:: xbzDs8WCdGtv\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => false,
                    'result' => true,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'dc',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'example',
                                    'getContent()' => 'example',
                                ]),
                            ]),
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'comment',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_BASE64,
                                    'getSpec()' => 'xbzDs8WCdGtv',
                                    'getContent()' => 'żółtko',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 57]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provParse
     */
    public function testParse(array $source, array $args, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(LdifAttrValRecordInterface::class)->getMockForAbstractClass();
        }

        $rule = new LdifAttrValRecordRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertImplementsInterface(LdifAttrValRecordInterface::class, $value);
            $this->assertObjectPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
