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

use Korowai\Lib\Ldif\Rules\AbstractRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\AbstractRule
 */
final class AbstractRuleTest extends TestCase
{
    public function test__implements__RuleInterface() : void
    {
        $this->assertImplementsInterface(RuleInterface::class, AbstractRule::class);
    }

    public static function prov__repeat()
    {
        $rule = new class extends AbstractRule {
            public function parse(ParserStateInterface $state, &$value = null, bool $trying = false) : bool
            {
                $cursor = $state->getCursor();
                $subject = $cursor->getString();
                $m = preg_match(
                    '/\G(?<tag>\w+):\s*(?<val>\w+)\n/D',
                    $subject,
                    $matches,
                    PREG_UNMATCHED_AS_NULL|PREG_OFFSET_CAPTURE,
                    $cursor->getOffset()
                );
                if (!$m) {
                    if (!$trying) {
                        $state->errorHere("syntax error");
                    }
                    $value = null;
                    return false;
                }

                $cursor->moveTo($matches[0][1] + strlen($matches[0][0]));
                $value = $matches['val'];
                return true;
            }
        };

        return [
            // #0
            [
                'rule'      => $rule,
                'source'    => ["", 0],
                'args'      => [],
                'expect'    => [
                    'result' => true,
                    'value' => [],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getErrors()' => [
                        ],
                    ],
                ],
            ],

            // #1
            [
                'rule'      => $rule,
                'source'    => ["", 0],
                'args'      => [1],
                'expect'    => [
                    'result' => false,
                    'value' => [],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error',
                            ]),
                        ],
                    ],
                ],
            ],

            //
            [
                'rule'      => $rule,
                //               00000000 0011111
                //               01234567 8901234
                'source'    => ["foo: FOO\nbar:", 0],
                'args'      => [1],
                'expect'    => [
                    'result' => true,
                    'value' => [['FOO', 5]],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getErrors()' => [
                        ],
                    ],
                ],
            ],

            //
            [
                'rule'      => $rule,
                //               00000000 0011111
                //               01234567 8901234
                'source'    => ["foo: FOO\nbar:", 0],
                'args'      => [2],
                'expect'    => [
                    'result' => false,
                    'value' => [['FOO', 5]],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 9,
                                'getMessage()' => 'syntax error',
                            ]),
                        ],
                    ],
                ],
            ],

            //
            [
                'rule'      => $rule,
                //               00000000 001111111 11
                //               01234567 890123456 78
                'source'    => ["foo: FOO\nbar: BAR\n", 0],
                'args'      => [2],
                'expect'    => [
                    'result' => true,
                    'value' => [['FOO', 5], ['BAR', 14]],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 18,
                        ]),
                        'getErrors()' => [
                        ],
                    ],
                ],
            ],

            //
            [
                'rule'      => $rule,
                //               00000000 001111111 11
                //               01234567 890123456 78
                'source'    => ["foo: FOO\nbar: BAR\n", 0],
                'args'      => [3],
                'expect'    => [
                    'result' => false,
                    'value' => [['FOO', 5], ['BAR', 14]],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 18,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 18,
                                'getMessage()' => 'syntax error',
                            ]),
                        ],
                    ],
                ],
            ],

            //
            [
                'rule'      => $rule,
                //               00000000 001111111 11
                //               01234567 890123456 78
                'source'    => ["foo: FOO\nbar: BAR\n", 0],
                'args'      => [0, 1],
                'expect'    => [
                    'result' => true,
                    'value' => [['FOO', 5]],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getErrors()' => [
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__repeat
     */
    public function test__repeat(RuleInterface $rule, array $source, array $args, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = $rule->repeat($state, $value, ...$args);
        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['value'], $value);
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
