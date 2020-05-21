<?php
/**
 * @file tests/Traits/HasNestedRulesTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\HasNestedRules;
use Korowai\Lib\Ldif\Exception\NoRulesDefinedException;
use Korowai\Lib\Ldif\Exception\InvalidRuleNameException;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class HasNestedRulesTest extends TestCase
{
    public static function getNestedRulesSpecs() : array
    {
        return [
            'dnSpecRule' => [
                'class'     => DnSpecRule::class,
                'optional'  => null,
                'construct' => [false],
            ],
            'sepRule' => [
                'class'     => SepRule::class,
                'optional'  => false,
                'construct' => [false],
            ],
            'controlRule' => [
                'class'     => ControlRule::class,
                'optional'  => true,
                'construct' => [true],
            ],
        ];
    }

    public static function getNestedRulesSpecs0() : array
    {
        return [];
    }

    public static function getNestedRulesSpecs1() : array
    {
        return array_slice(self::getNestedRulesSpecs(), 0, 1);
    }

    public static function getNestedRulesSpecs2() : array
    {
        return array_slice(self::getNestedRulesSpecs(), 0, 2);
    }

    public static function getTestObject0(array $rules = null)
    {
        if ($rules === null) {
            return new class {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs0();
                }
            };
        } else {
            return new class ($rules) {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public function __construct(array $rules = []) {
                    $this->initNestedRules($rules);
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs0();
                }
            };
        }
    }

    public static function getTestObject1(array $rules = null)
    {
        if ($rules === null) {
            return new class {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs1();
                }
            };
        } else {
            return new class ($rules) {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public function __construct(array $rules = []) {
                    $this->initNestedRules($rules);
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs1();
                }
            };
        }
    }

    public static function getTestObject2(array $rules = null)
    {
        if ($rules === null) {
            return new class {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs2();
                }
            };
        } else {
            return new class ($rules) {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public function __construct(array $rules = []) {
                    $this->initNestedRules($rules);
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs2();
                }
            };
        }
    }

    public static function getTestObject(array $rules = null)
    {
        if ($rules === null) {
            return new class {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs();
                }
            };
        } else {
            return new class ($rules) {
                use HasNestedRules {
                    initNestedRules as public;
                    initNestedRule as public;
                }

                public function __construct(array $rules = []) {
                    $this->initNestedRules($rules);
                }

                public static function getNestedRulesSpecs() : array {
                    return HasNestedRulesTest::getNestedRulesSpecs();
                }
            };
        }
    }

    public function test__getNestedRulesSpecs()
    {
        $object = $this->getTestObject();
        $class = get_class($object);
        $this->assertSame(static::getNestedRulesSpecs(), $class::getNestedRulesSpecs());
    }

    public function test__getNestedRules__uninitialized()
    {
        $object = $this->getTestObject();

        $this->assertSame([], $object->getNestedRules());
    }

    public function test__getNestedRules__defaultInitialized()
    {
        $object = $this->getTestObject([]);

        $specs = self::getNestedRulesSpecs();
        $rules = $object->getNestedRules();

        $this->assertIsArray($rules);
        $this->assertCount(3, $rules);
        $this->assertInstanceOf(DnSpecRule::class, $rules['dnSpecRule']);
        $this->assertInstanceOf(SepRule::class, $rules['sepRule']);
        $this->assertInstanceOf(ControlRule::class, $rules['controlRule']);

        $this->assertSame(false, $rules['dnSpecRule']->isOptional());
        $this->assertSame(false, $rules['sepRule']->isOptional());
        $this->assertSame(true, $rules['controlRule']->isOptional());
    }

    public function test__getNestedRules__userInitialized()
    {
        $dnSpecRule = new DnSpecRule(false);
        $sepRule = new SepRule(false);

        $object = $this->getTestObject([
            'dnSpecRule' => $dnSpecRule,
            'sepRule' => $sepRule
        ]);

        $rules = $object->getNestedRules();
        $this->assertSame($dnSpecRule, $rules['dnSpecRule']);
        $this->assertSame($sepRule, $rules['sepRule']);

        $this->assertInstanceOf(ControlRule::class, $rules['controlRule']);
        $this->assertSame(true, $rules['controlRule']->isOptional());
    }

    public function test__getNestedRule__uninitialized()
    {
        $object = $this->getTestObject();
        $qclass = preg_quote(get_class($object), '/');

        $this->expectException(NoRulesDefinedException::class);
        $this->expectExceptionMessageMatches(
            '/^'.$qclass.'::getNestedRule\(\) can\'t be used, no nested rules set\.$/'
        );

        $object->getNestedRule('dnSpecRule');
    }

    public function test__getNestedRule__withInvalidKey__1()
    {
        $object = $this->getTestObject1([]);
        $qclass = preg_quote(get_class($object), '/');

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::getNestedRule\(\) must be exactly "dnSpecRule", "foo" given\.$/'
        );

        $object->getNestedRule('foo');
    }

    public function test__getNestedRule__withInvalidKey__2()
    {
        $object = $this->getTestObject2([]);
        $qclass = preg_quote(get_class($object), '/');

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::getNestedRule\(\) must be either "dnSpecRule" or "sepRule", "foo" given\.$/'
        );

        $object->getNestedRule('foo');
    }

    public function test__getNestedRule__withInvalidKey__3()
    {
        $object = $this->getTestObject([]);
        $qclass = preg_quote(get_class($object), '/');

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::getNestedRule\(\) must be one of "dnSpecRule", "sepRule", or "controlRule", '.
            '"foo" given\.$/'
        );

        $object->getNestedRule('foo');
    }

    public function test__getNestedRule__defaultInitialized()
    {
        $object = $this->getTestObject([]);

        $dnSpecRule = $object->getNestedRule('dnSpecRule');
        $sepRule = $object->getNestedRule('sepRule');
        $controlRule = $object->getNestedRule('controlRule');
        $this->assertInstanceOf(DnSpecRule::class, $dnSpecRule);
        $this->assertInstanceOf(SepRule::class, $sepRule);
        $this->assertInstanceOf(ControlRule::class, $controlRule);
    }

    public function test__setNestedRule()
    {
        $object = $this->getTestObject();
        $dnSpecRule = new DnSpecRule(true);

        $this->assertSame($object, $object->setNestedRule('dnSpecRule', $dnSpecRule));
        $this->assertSame($dnSpecRule, $object->getNestedRule('dnSpecRule'));
    }

    public function test__setNestedRule__withNoRules()
    {
        $object = $this->getTestObject0();
        $qclass = preg_quote(get_class($object), '/');
        $sepRule = new SepRule();

        $this->expectException(NoRulesDefinedException::class);
        $this->expectExceptionMessageMatches(
            '/^'.$qclass.'::setNestedRule\(\) can\'t be used, no nested rules defined\.$/'
        );

        $object->setNestedRule('dnSpecRule', $sepRule);
    }

    public function test__setNestedRule__withInvalidKey()
    {
        $object = $this->getTestObject();
        $qclass = preg_quote(get_class($object), '/');
        $sepRule = new SepRule();

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::setNestedRule\(\) must be one of "dnSpecRule", "sepRule", or "controlRule", '.
            '"foo" given\.$/'
        );

        $object->setNestedRule('foo', $sepRule);
    }

    public function test__setNestedRule__withInvalidRuleClass()
    {
        $object = $this->getTestObject();
        $qclass = preg_quote(get_class($object), '/');
        $sepRule = new SepRule();

        $this->expectException(InvalidRuleClassException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument \$rule in '.$qclass.'::setNestedRule\("dnSpecRule", \$rule\) must be an instance of '.
            preg_quote(DnSpecRule::class).', instance of '.preg_quote(SepRule::class).' given\.$/'
        );

        $object->setNestedRule('dnSpecRule', $sepRule);
    }

    public function test__setNestedRule__withInvalidOptionalFlag()
    {
        $object = $this->getTestObject();
        $qclass = preg_quote(get_class($object), '/');
        $sepRule = new SepRule(true);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument \$rule in '.$qclass.'::setNestedRule\("sepRule", \$rule\) must satisfy '.
            '\$rule->isOptional\(\) === false\.$/'
        );
        $object->setNestedRule('sepRule', $sepRule);
    }

    public function test__getNestedRuleSpec__noNestedRulesDefined()
    {
        $object = $this->getTestObject0();
        $class = get_class($object);
        $qclass = preg_quote($class, '/');

        $this->expectException(NoRulesDefinedException::class);
        $this->expectExceptionMessageMatches(
            '/^'.$qclass.'::getNestedRuleSpec\(\) can\'t be used, no nested rules defined\.$/'
        );

        $class::getNestedRuleSpec('dnSpec');
    }

    public function test__getNestedRuleSpec__invalidKey__1()
    {
        $object = $this->getTestObject1();
        $class = get_class($object);
        $qclass = preg_quote($class, '/');

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::getNestedRuleSpec\(\) must be exactly "dnSpecRule", "foo" given\.$/'
        );

        $class::getNestedRuleSpec('foo');
    }

    public function test__getNestedRuleSpec__invalidKey__2()
    {
        $object = $this->getTestObject2();
        $class = get_class($object);
        $qclass = preg_quote($class, '/');

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::getNestedRuleSpec\(\) must be either "dnSpecRule" or "sepRule", '.
            '"foo" given\.$/'
        );

        $class::getNestedRuleSpec('foo');
    }

    public function test__getNestedRuleSpec__invalidKey__3()
    {
        $object = $this->getTestObject();
        $class = get_class($object);
        $qclass = preg_quote($class, '/');

        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches(
            '/^Argument 1 to '.$qclass.'::getNestedRuleSpec\(\) must be one of "dnSpecRule", "sepRule", or '.
            '"controlRule", "foo" given\.$/'
        );

        $class::getNestedRuleSpec('foo');
    }


    public function test__initNestedRules__defaults()
    {
        $object = $this->getTestObject();

        $this->assertSame($object, $object->initNestedRules());

        $rules = $object->getNestedRules();

        $this->assertIsArray($rules);
        $this->assertCount(3, $rules);
        $this->assertInstanceOf(DnSpecRule::class, $rules['dnSpecRule']);
        $this->assertInstanceOf(SepRule::class, $rules['sepRule']);
        $this->assertInstanceOf(ControlRule::class, $rules['controlRule']);

        $this->assertSame(false, $rules['dnSpecRule']->isOptional());
        $this->assertSame(false, $rules['sepRule']->isOptional());
        $this->assertSame(true, $rules['controlRule']->isOptional());
    }

    public function test__initNestedRules__withUserRules()
    {
        $object = $this->getTestObject();

        $dnSpecRule = new DnSpecRule;
        $sepRule = new SepRule;
        $controlRule = new ControlRule(true);

        $this->assertSame($object, $object->initNestedRules([
            'dnSpecRule' => $dnSpecRule,
            'sepRule' => $sepRule,
            'controlRule' => $controlRule,
        ]));

        $this->assertSame($dnSpecRule, $object->getNestedRule('dnSpecRule'));
        $this->assertSame($sepRule, $object->getNestedRule('sepRule'));
        $this->assertSame($controlRule, $object->getNestedRule('controlRule'));
    }
}

// vim: syntax=php sw=4 ts=4 et:
