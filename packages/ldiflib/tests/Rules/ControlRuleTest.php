<?php
/**
 * @file tests/Rules/ControlRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ValueSpecRule;
use Korowai\Lib\Ldif\Rules\AbstractRule;
use Korowai\Lib\Ldif\ControlInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ControlRuleTest extends TestCase
{
    public function test__extendsAbstractRule()
    {
        $this->assertExtendsClass(AbstractRule::class, ControlRule::class);
    }

    public static function construct__cases()
    {
        $valueSpecRule = new ValueSpecRule;

        return [
            'default' => [
                'args'   => [],
                'expect' => [
                    'isOptional()' => false
                ]
            ],
            'required' => [
                'args'   => [false],
                'expect' => [
                    'isOptional()' => false
                ]
            ],
            'optional' => [
                'args'   => [true],
                'expect' => [
                    'isOptional()' => true
                ]
            ],
            'valueSpecRule' => [
                'args'   => [false, $valueSpecRule],
                'expect' => [
                    'isOptional()' => false,
                    'getValueSpecRule()' => $valueSpecRule
                ]
            ]
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $rule = new ControlRule(...$args);
        $this->assertHasPropertiesSameAs($expect, $rule);

        if (null === ($expect['getValueSpecRule()'] ?? null)) {
            $this->assertInstanceOf(ValueSpecRule::class, $rule->getValueSpecRule());
        }
    }

    public function test__valueSpecRule()
    {
        $rule = new ControlRule;
        $vsRule = new ValueSpecRule;

        $this->assertNotNull($rule->getValueSpecRule());
        $this->assertSame($rule, $rule->setValueSpecRule($vsRule));
        $this->assertSame($vsRule, $rule->getValueSpecRule());
    }

    //
    // parseMatched()
    //
    public static function parseMatched__cases()
    {
        return [
            'valid w/o crit w/o value' => [
                //            000000000011111111
                //            012345678901234567
                'source' => ['control: 1.22.333', 17],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => null,
                        'valueObject' => null,
                    ],
                    'state' => [
                        'cursor' => ['offset' => 17],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],

            'valid w/ crit = true w/o value' => [
                //            00000000001111111111222
                //            01234567890123456789012
                'source' => ['control: 1.22.333 true', 22],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['true', 18],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => true,
                        'valueObject' => null,
                    ],
                    'state' => [
                        'cursor' => ['offset' => 22],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],

            'valid w/ crit = false w/o value' => [
                //            000000000011111111112222
                //            012345678901234567890123
                'source' => ['control: 1.22.333 false', 23],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['false', 18],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => false,
                        'valueObject' => null,
                    ],
                    'state' => [
                        'cursor' => ['offset' => 23],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],

            'invalid w/ crit = foo w/o value' => [
                //            0000000000111111111122
                //            0123456789012345678901
                'source' => ['control: 1.22.333 foo', 21],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['foo', 18],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [
                            [
                                'sourceOffset' => 18,
                                'message' => 'syntax error: invalid control criticality: "foo"'
                            ]
                        ],
                        'records' => [],
                    ]
                ]
            ],

            'valid w/o crit w/ safe-string value' => [
                //            00000000001111111111222
                //            01234567890123456789012
                'source' => ['control: 1.22.333: FOO', 22],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'value_safe' => ['FOO', 19],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => null,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'FOO',
                            'content' => 'FOO',
                        ],
                    ],
                    'state' => [
                        'cursor' => ['offset' => 22],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],

            'valid w/ crit = true w/ safe-string value' => [
                //            0000000000111111111122222222
                //            0123456789012345678901234567
                'source' => ['control: 1.22.333 true: FOO', 27],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['true', 18],
                    'value_safe' => ['FOO', 24],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => true,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'FOO',
                            'content' => 'FOO',
                        ],
                    ],
                    'state' => [
                        'cursor' => ['offset' => 27],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],

            'invalid w/ malformed BASE64-STRING value' => [
                //            0000000000111111111122
                //            0123456789012345678901
                'source' => ['control: 1.22.333:: R', 21],
                'matches' => [
                    'ctl_type'  => ['1.22.333', 9],
                    'value_b64' => ['R', 20]
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [
                            [
                                'sourceOffset' => 20,
                                'message' => 'syntax error: invalid BASE64 string',
                            ],
                        ],
                        'records' => [],
                    ]
                ]
            ],

            'invalid w/ missing ctl_type' => [
                'source' => ['AAA', 21],
                'matches' => [
                    'value_safe' => ['AAA', 18],
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [
                            [
                                'sourceOffset' => 21,
                                'message' => 'internal error: missing or invalid capture group "ctl_type"'
                            ],
                        ],
                        'records' => [],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider parseMatched__cases
     */
    public function test__parseMatched(array $source, array $matches, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        }

        $rule = new ControlRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertControlHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function parse__cases()
    {
        return [
            'empty string' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [
                            [
                                'sourceOffset' => 0,
                                'message' => 'syntax error: expected "control:" (RFC2849)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'empty string (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            '"foo: FOO" (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['foo: FOO', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            '"control" w/o colon' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [
                            [
                                'sourceOffset' => 0,
                                'message' => 'syntax error: expected "control:" (RFC2849)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            '"control" w/o colon (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: ' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: ', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 9],
                        'errors' => [
                            [
                                'sourceOffset' => 9,
                                'message' => 'syntax error: missing or invalid OID (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 14],
                        'errors' => [
                            [
                                'sourceOffset' => 13,
                                'message' => 'syntax error: missing or invalid OID (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.$%^' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.$%^', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 14],
                        'errors' => [
                            [
                                'sourceOffset' => 10,
                                'message' => 'syntax error: missing or invalid OID (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: asdfg' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: asdfg', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 14],
                        'errors' => [
                            [
                                'sourceOffset' => 9,
                                'message' => 'syntax error: missing or invalid OID (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333 foo' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 foo', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [
                            [
                                'sourceOffset' => 18,
                                'message' => 'syntax error: expected "true" or "false" (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333 foo: bar' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 foo: bar', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 26],
                        'errors' => [
                            [
                                'sourceOffset' => 18,
                                'message' => 'syntax error: expected "true" or "false" (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            '"control: 1.22.333 "' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 ', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 18],
                        'errors' => [
                            [
                                'sourceOffset' => 18,
                                'message' => 'syntax error: expected "true" or "false" (RFC2849)'
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => null,
                        'valueObject' => null,
                    ],
                    'state' => [
                        'cursor' => ['offset' => 17],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333 true' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 true', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => true,
                        'valueObject' => null,
                    ],
                    'state' => [
                        'cursor' => ['offset' => 22],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333 false' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 false', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => false,
                        'valueObject' => null,
                    ],
                    'state' => [
                        'cursor' => ['offset' => 23],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333: FOO', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => null,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'FOO',
                            'content' => 'FOO',
                        ]
                    ],
                    'state' => [
                        'cursor' => ['offset' => 22],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333 true: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 true: FOO', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => true,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'FOO',
                            'content' => 'FOO',
                        ]
                    ],
                    'state' => [
                        'cursor' => ['offset' => 27],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333: <value_safe_error>' => [
                //            0000000000111111111222222222233333
                //            0123456789012345678901234567890123
                'source' => ['control: 1.22.333: FOOŁXXX', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 27],
                        'errors' => [
                            [
                                'sourceOffset' => 22,
                                'message' => 'syntax error: malformed SAFE-STRING (RFC2849)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333:: <value_b64>' => [
                //            000000000011111111112222222222333333333344444
                //            012345678901234567890123456789012345678901234
                'source' => ['control: 1.22.333:: xbvDs8WCdGEgxYHDs2TFug==', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => null,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_BASE64,
                            'spec' => 'xbvDs8WCdGEgxYHDs2TFug==',
                            'content' => 'Żółta Łódź',
                        ]
                    ],
                    'state' => [
                        'cursor' => ['offset' => 44],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333 true:: <value_b64>' => [
                //            00000000001111111111222222222233333333334444444444
                //            01234567890123456789012345678901234567890123456789
                'source' => ['control: 1.22.333 true:: xbvDs8WCdGEgxYHDs2TFug==', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => true,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_BASE64,
                            'spec' => 'xbvDs8WCdGEgxYHDs2TFug==',
                            'content' => 'Żółta Łódź',
                        ]
                    ],
                    'state' => [
                        'cursor' => ['offset' => 49],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333:: <value_b64_error>' => [
                //            00000000001111111112222222222333333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333:: xbvDł8W', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 28],
                        'errors' => [
                            [
                                'sourceOffset' => 24,
                                'message' => 'syntax error: malformed BASE64-STRING (RFC2849)'
                            ],
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333:: <value_b64_invalid>' => [
                //            00000000001111111112222222222333333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333:: R', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [
                            [
                                'sourceOffset' => 20,
                                'message' => 'syntax error: invalid BASE64 string'
                            ],
                        ],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333:< <value_url>' => [
                //            000000000011111111112222222222333333333344444444
                //            012345678901234567890123456789012345678901234567
                'source' => ['control: 1.22.333:< file:///home/jsmith/foo.txt', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'oid' => '1.22.333',
                        'criticality' => null,
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_URL,
                            'spec' => [
                                'string' => 'file:///home/jsmith/foo.txt',
                                'scheme' => 'file',
                                'authority' => '',
                                'userinfo' => null,
                                'host' => '',
                                'port' => null,
                                'path' => '/home/jsmith/foo.txt',
                                'query' => null,
                                'fragment' => null,
                            ]
                            //'value_url' => 'file:///home/jsmith/foo.txt',
                        ],
                    ],
                    'state' => [
                        'cursor' => ['offset' => 47],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'control: 1.22.333:< <value_url_error>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['control: 1.22.333:< ##', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 22],
                        'errors' => [
                            [
                                'sourceOffset' => 21,
                                'message' => 'syntax error: malformed URL (RFC2849/RFC3986)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],
        ];
    }


    /**
     * @dataProvider parse__cases
     */
    public function test__parse(array $source, array $args, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        }

        $rule = new ControlRule(...$args);

        $result = $rule->parse($state, $value);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertControlHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et: