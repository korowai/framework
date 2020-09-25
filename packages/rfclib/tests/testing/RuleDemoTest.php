<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Rfclib;

use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Testing\Rfclib\RuleDemo;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Rfclib\RuleDemo
 *
 * @internal
 */
final class RuleDemoTest extends TestCase
{
    public static function provConstruct(): array
    {
        $rule = new Rule(Rfc2849::class, 'DN_SPEC');

        return [
            '__construct($rule)' => [
                [$rule],
                [
                    'getRule()' => $rule,
                    'getFormat()' => '/\G%s/D',
                ],
            ],
            '__construct($rule, "/^%s$/")' => [
                [$rule, '/^%s$/'],
                [
                    'getRule()' => $rule,
                    'getFormat()' => '/^%s$/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $demo = new RuleDemo(...$args);
        $this->assertInstanceOf(RuleInterface::class, $demo->getRule());
        $this->assertIsString($demo->getFormat());
        $this->assertObjectPropertiesIdenticalTo($expect, $demo);
    }

    public static function provCreate(): array
    {
        return [
            'create(Rfc2849::class, "DN_SPEC")' => [
                [Rfc2849::class, 'DN_SPEC'],
                [
                    'getRule()' => self::objectPropertiesIdenticalTo([
                        'ruleSetClass()' => Rfc2849::class,
                        'name()' => 'DN_SPEC',
                    ]),
                    'getFormat()' => '/\G%s/D',
                ],
            ],
            'create(Rfc2849::class, "DN_SPEC", "/^%s$/")' => [
                [Rfc2849::class, 'DN_SPEC', '/^%s$/'],
                [
                    'getRule()' => self::objectPropertiesIdenticalTo([
                        'ruleSetClass()' => Rfc2849::class,
                        'name()' => 'DN_SPEC',
                    ]),
                    'getFormat()' => '/^%s$/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provCreate
     */
    public function testCreate(array $args, array $expect): void
    {
        $demo = RuleDemo::create(...$args);
        $this->assertInstanceOf(RuleInterface::class, $demo->getRule());
        $this->assertIsString($demo->getFormat());
        $this->assertObjectPropertiesIdenticalTo($expect, $demo);
    }

    public function testSetRule(): void
    {
        $demo = RuleDemo::create(Rfc2849::class, 'DN_SPEC');
        $rule = new Rule(Rfc2849::class, 'VALUE_SPEC');

        $this->assertSame($demo, $demo->setRule($rule));
        $this->assertSame($rule, $demo->getRule());
    }

    public function testSetFormat(): void
    {
        $demo = RuleDemo::create(Rfc2849::class, 'DN_SPEC');

        $this->assertSame($demo, $demo->setFormat('/^%s$/'));
        $this->assertSame('/^%s$/', $demo->getFormat());
    }

    public function testGetRegex(): void
    {
        $rule = new Rule(Rfc2849::class, 'DIGIT');
        $demo = new RuleDemo($rule);

        $this->assertSame('/\G'.(string) $rule.'/D', $demo->regex());
        $this->assertSame($demo, $demo->setFormat('/^%s$/'));
        $this->assertSame('/^'.(string) $rule.'$/', $demo->regex());
    }

    public function testQuote(): void
    {
        $this->assertSame('"foo\\nbar\\tgez\\"qux"', RuleDemo::quote("foo\nbar\tgez\"qux"));
    }

    public function testFilterCaptures(): void
    {
        $this->assertSame(
            [
                ['0', 0],
                'a' => 'A',
                'd' => ['D', 4],
            ],
            RuleDemo::filterCaptures([
                ['0', 0],
                'a' => 'A',
                'b' => null,
                'c' => [null, -1],
                2 => '2',
                'd' => ['D', 4],
                3 => ['3', 5],
            ])
        );
    }

    public static function provMatchAndGetReport(): array
    {
        $demo = RuleDemo::create(Rfc2849::class, 'DN_SPEC');

        return [
            'foo: ' => [
                'demo' => $demo,
                'args' => ['foo: '],
                'expect' => [
                    'result' => false,
                ],
            ],

            'dn: dc=example,dc=org\n' => [
                'demo' => $demo,
                'args' => ["dn: dc=example,dc=org\n"],
                'expect' => [
                    'result' => true,
                    'matches' => [
                        0 => 'dn: dc=example,dc=org',
                        'value_safe' => 'dc=example,dc=org',
                    ],
                ],
            ],

            'dn: dc=example,dc=org\n' => [
                'demo' => $demo,
                'args' => ["dn: dc=example,dc=org\n", PREG_OFFSET_CAPTURE],
                'expect' => [
                    'result' => true,
                    'matches' => [
                        0 => ['dn: dc=example,dc=org', 0],
                        'value_safe' => ['dc=example,dc=org', 4],
                    ],
                ],
            ],

            'dn: dc=pomyłka,dc=org\n' => [
                'demo' => $demo,
                'args' => ["dn: dc=pomyłka,dc=org\n", PREG_OFFSET_CAPTURE],
                'expect' => [
                    'result' => true,
                    'matches' => [
                        0 => ['dn: dc=pomyłka,dc=org', 0],
                        'value_safe_error' => ['łka,dc=org', 11],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provMatchAndGetReport
     */
    public function testMatchAndGetReport(RuleDemo $demo, array $args, array $expect): void
    {
        $report = $demo->matchAndGetReport(...$args);
        $subject = $args[0];
        $qsubject = $demo->quote($subject);
        if ($expect['result']) {
            $matches = json_encode($expect['matches'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->assertSame(sprintf("matched: %s\nmatches: %s", $qsubject, $matches), $report);
        } else {
            $this->assertSame(sprintf('failed: %s', $qsubject), $report);
        }
    }

    /**
     * @dataProvider provMatchAndGetReport
     */
    public function testMatchAndReport(RuleDemo $demo, array $args, array $expect): void
    {
        $subject = $args[0];
        $qsubject = $demo->quote($subject);
        if ($expect['result']) {
            $matches = json_encode($expect['matches'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->expectOutputString(sprintf("matched: %s\nmatches: %s\n-\n", $qsubject, $matches));
        } else {
            $this->expectOutputString(sprintf("failed: %s\n-\n", $qsubject));
        }
        $report = $demo->matchAndReport(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
