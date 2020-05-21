<?php
/**
 * @file tests/Traits/LdifChangeRecordNestedRulesTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\LdifChangeRecordNestedRules;
use Korowai\Lib\Ldif\Traits\HasNestedRules;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangeRecordNestedRulesTest extends TestCase
{
    public function test__uses__HasNestedRules()
    {
        $this->assertUsesTrait(HasNestedRules::class, LdifChangeRecordNestedRules::class);
    }

    public function test__getNestedRulesSpecs()
    {
        $object = $this->getMockForTrait(LdifChangeRecordNestedRules::class);

        $this->assertSame([
                'dnSpecRule'            => [
                    'class'             => DnSpecRule::class,
                    'optional'          => null,
                    'construct'         => [true],
                ],
                'controlRule'           => [
                    'class'             => ControlRule::class,
                    'optional'          => true,
                    'construct'         => [true],
                ],
                'changeRecordInitRule'  => [
                    'class'             => ChangeRecordInitRule::class,
                    'optional'          => false,
                    'construct'         => [false],
                ],
                'modSpecRule'           => [
                    'class'             => ModSpecRule::class,
                    'optional'          => true,
                    'construct'         => [true],
                ],
                'sepRule'               => [
                    'class'             => SepRule::class,
                    'optional'          => false,
                    'construct'         => [false],
                ],
                'attrValSpecRule'       => [
                    'class'             => AttrValSpecRule::class,
                    'optional'          => false,
                    'construct'         => [false],
                ],
            ],
            get_class($object)::getNestedRulesSpecs()
        );
    }

    public function test__getDnSpecRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['getNestedRule'])
                       ->getMockForTrait();
        $rule = new DnSpecRule;

        $object->expects($this->once())
               ->method('getNestedRule')
               ->with('dnSpecRule')
               ->will($this->returnValue($rule));

        $this->assertSame($rule, $object->getDnSpecRule());
    }

    public function test__setDnSpecRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['setNestedRule'])
                       ->getMockForTrait();
        $rule = new DnSpecRule;

        $object->expects($this->once())
               ->method('setNestedRule')
               ->with('dnSpecRule', $rule)
               ->will($this->returnValue($object));

        $this->assertSame($object, $object->setDnSpecRule($rule));
    }

    public function test__getControlRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['getNestedRule'])
                       ->getMockForTrait();
        $rule = new ControlRule;

        $object->expects($this->once())
               ->method('getNestedRule')
               ->with('controlRule')
               ->will($this->returnValue($rule));

        $this->assertSame($rule, $object->getControlRule());
    }

    public function test__setControlRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['setNestedRule'])
                       ->getMockForTrait();
        $rule = new ControlRule;

        $object->expects($this->once())
               ->method('setNestedRule')
               ->with('controlRule', $rule)
               ->will($this->returnValue($object));

        $this->assertSame($object, $object->setControlRule($rule));
    }

    public function test__getChangeRecordInitRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['getNestedRule'])
                       ->getMockForTrait();
        $rule = new ChangeRecordInitRule;

        $object->expects($this->once())
               ->method('getNestedRule')
               ->with('changeRecordInitRule')
               ->will($this->returnValue($rule));

        $this->assertSame($rule, $object->getChangeRecordInitRule());
    }

    public function test__setChangeRecordInitRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['setNestedRule'])
                       ->getMockForTrait();
        $rule = new ChangeRecordInitRule;

        $object->expects($this->once())
               ->method('setNestedRule')
               ->with('changeRecordInitRule', $rule)
               ->will($this->returnValue($object));

        $this->assertSame($object, $object->setChangeRecordInitRule($rule));
    }

    public function test__getModSpecRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['getNestedRule'])
                       ->getMockForTrait();
        $rule = new ModSpecRule;

        $object->expects($this->once())
               ->method('getNestedRule')
               ->with('modSpecRule')
               ->will($this->returnValue($rule));

        $this->assertSame($rule, $object->getModSpecRule());
    }

    public function test__setModSpecRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['setNestedRule'])
                       ->getMockForTrait();
        $rule = new ModSpecRule;

        $object->expects($this->once())
               ->method('setNestedRule')
               ->with('modSpecRule', $rule)
               ->will($this->returnValue($object));

        $this->assertSame($object, $object->setModSpecRule($rule));
    }

    public function test__getSepRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['getNestedRule'])
                       ->getMockForTrait();
        $rule = new SepRule;

        $object->expects($this->once())
               ->method('getNestedRule')
               ->with('sepRule')
               ->will($this->returnValue($rule));

        $this->assertSame($rule, $object->getSepRule());
    }

    public function test__setSepRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['setNestedRule'])
                       ->getMockForTrait();
        $rule = new SepRule;

        $object->expects($this->once())
               ->method('setNestedRule')
               ->with('sepRule', $rule)
               ->will($this->returnValue($object));

        $this->assertSame($object, $object->setSepRule($rule));
    }

    public function test__getAttrValSpecRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['getNestedRule'])
                       ->getMockForTrait();
        $rule = new AttrValSpecRule;

        $object->expects($this->once())
               ->method('getNestedRule')
               ->with('attrValSpecRule')
               ->will($this->returnValue($rule));

        $this->assertSame($rule, $object->getAttrValSpecRule());
    }

    public function test__setAttrValSpecRule()
    {
        $object = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                       ->setMethods(['setNestedRule'])
                       ->getMockForTrait();
        $rule = new AttrValSpecRule;

        $object->expects($this->once())
               ->method('setNestedRule')
               ->with('attrValSpecRule', $rule)
               ->will($this->returnValue($object));

        $this->assertSame($object, $object->setAttrValSpecRule($rule));
    }


//    public function test__getNestedRulesSpecs()
//    {
//        $object = $this->getTestObject();
//        $this->assertSame(static::getNestedRulesSpecs(), $object->getNestedRulesSpecs());
//    }
//
//    public function test__getNestedRules__uninitialized()
//    {
//        $object = $this->getTestObject();
//
//        $this->assertSame([], $object->getNestedRules());
//    }
//
//    public function test__getNestedRules__defaultInitialized()
//    {
//        $object = $this->getTestObject([]);
//
//        $specs = self::getNestedRulesSpecs();
//        $rules = $object->getNestedRules();
//
//        $this->assertIsArray($rules);
//        $this->assertCount(3, $rules);
//        $this->assertInstanceOf(DnSpecRule::class, $rules['dnSpecRule']);
//        $this->assertInstanceOf(SepRule::class, $rules['sepRule']);
//        $this->assertInstanceOf(ControlRule::class, $rules['controlRule']);
//
//        $this->assertSame(false, $rules['dnSpecRule']->isOptional());
//        $this->assertSame(false, $rules['sepRule']->isOptional());
//        $this->assertSame(true, $rules['controlRule']->isOptional());
//    }
//
//    public function test__getNestedRules__userInitialized()
//    {
//        $dnSpecRule = new DnSpecRule(false);
//        $sepRule = new SepRule(false);
//
//        $object = $this->getTestObject([
//            'dnSpecRule' => $dnSpecRule,
//            'sepRule' => $sepRule
//        ]);
//
//        $rules = $object->getNestedRules();
//        $this->assertSame($dnSpecRule, $rules['dnSpecRule']);
//        $this->assertSame($sepRule, $rules['sepRule']);
//
//        $this->assertInstanceOf(ControlRule::class, $rules['controlRule']);
//        $this->assertSame(true, $rules['controlRule']->isOptional());
//    }
//
//    public function test__getNestedRule__uninitialized()
//    {
//        $object = $this->getTestObject();
//
//        $this->expectException(NoRulesDefinedException::class);
//        $this->expectExceptionMessageMatches('/^[^:]+::getNestedRule\(\) can\'t be used, no nested rules set.$/');
//
//        $object->getNestedRule('dnSpecRule');
//    }
//
//    public function test__getNestedRule__withInvalidKey__1()
//    {
//        $object = $this->getTestObject1([]);
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::getNestedRule\(\) must be exactly "dnSpecRule", "foo" given\.$/'
//        );
//
//        $object->getNestedRule('foo');
//    }
//
//    public function test__getNestedRule__withInvalidKey__2()
//    {
//        $object = $this->getTestObject2([]);
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::getNestedRule\(\) must be either "dnSpecRule" or "sepRule", "foo" given\.$/'
//        );
//
//        $object->getNestedRule('foo');
//    }
//
//    public function test__getNestedRule__withInvalidKey__3()
//    {
//        $object = $this->getTestObject([]);
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::getNestedRule\(\) must be one of "dnSpecRule", "sepRule", or "controlRule", '.
//            '"foo" given\.$/'
//        );
//
//        $object->getNestedRule('foo');
//    }
//
//    public function test__getNestedRule__defaultInitialized()
//    {
//        $object = $this->getTestObject([]);
//
//        $dnSpecRule = $object->getNestedRule('dnSpecRule');
//        $sepRule = $object->getNestedRule('sepRule');
//        $controlRule = $object->getNestedRule('controlRule');
//        $this->assertInstanceOf(DnSpecRule::class, $dnSpecRule);
//        $this->assertInstanceOf(SepRule::class, $sepRule);
//        $this->assertInstanceOf(ControlRule::class, $controlRule);
//    }
//
//    public function test__setNestedRule()
//    {
//        $object = $this->getTestObject();
//        $dnSpecRule = new DnSpecRule(true);
//
//        $this->assertSame($object, $object->setNestedRule('dnSpecRule', $dnSpecRule));
//        $this->assertSame($dnSpecRule, $object->getNestedRule('dnSpecRule'));
//    }
//
//    public function test__setNestedRule__withNoRules()
//    {
//        $object = $this->getTestObject0();
//        $sepRule = new SepRule();
//
//        $this->expectException(NoRulesDefinedException::class);
//        $this->expectExceptionMessageMatches(
//            '/^[^:]+::setNestedRule\(\) can\'t be used, no nested rules defined.$/'
//        );
//
//        $object->setNestedRule('dnSpecRule', $sepRule);
//    }
//
//    public function test__setNestedRule__withInvalidKey()
//    {
//        $object = $this->getTestObject();
//        $sepRule = new SepRule();
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::setNestedRule\(\) must be one of "dnSpecRule", "sepRule", or "controlRule", '.
//            '"foo" given.$/'
//        );
//
//        $object->setNestedRule('foo', $sepRule);
//    }
//
//    public function test__setNestedRule__withInvalidRuleClass()
//    {
//        $object = $this->getTestObject();
//        $sepRule = new SepRule();
//
//        $this->expectException(InvalidRuleClassException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument \$rule in [^:]+::setNestedRule\("dnSpecRule", \$rule\) must be an instance of '.
//            preg_quote(DnSpecRule::class).', instance of '.preg_quote(SepRule::class).' given\.$/'
//        );
//
//        $object->setNestedRule('dnSpecRule', $sepRule);
//    }
//
//    public function test__setNestedRule__withInvalidOptionalFlag()
//    {
//        $object = $this->getTestObject();
//        $sepRule = new SepRule(true);
//
//        $this->expectException(\InvalidArgumentException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument \$rule in [^:]+::setNestedRule\("sepRule", \$rule\) must satisfy '.
//            '\$rule->isOptional\(\) === false\.$/'
//        );
//        $object->setNestedRule('sepRule', $sepRule);
//    }
//
//    public function test__getNestedRuleSpec__noNestedRulesDefined()
//    {
//        $object = $this->getTestObject0();
//
//        $this->expectException(NoRulesDefinedException::class);
//        $this->expectExceptionMessageMatches('/^[^:]+::getNestedRuleSpec\(\) can\'t be used, no nested rules defined.$/');
//
//        $object->getNestedRuleSpec('dnSpec');
//    }
//
//    public function test__getNestedRuleSpec__invalidKey__1()
//    {
//        $object = $this->getTestObject1();
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::getNestedRuleSpec\(\) must be exactly "dnSpecRule", "foo" given\.$/'
//        );
//
//        $object->getNestedRuleSpec('foo');
//    }
//
//    public function test__getNestedRuleSpec__invalidKey__2()
//    {
//        $object = $this->getTestObject2();
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::getNestedRuleSpec\(\) must be either "dnSpecRule" or "sepRule", "foo" given\.$/'
//        );
//
//        $object->getNestedRuleSpec('foo');
//    }
//
//    public function test__getNestedRuleSpec__invalidKey__3()
//    {
//        $object = $this->getTestObject();
//
//        $this->expectException(InvalidRuleNameException::class);
//        $this->expectExceptionMessageMatches(
//            '/^Argument 1 to [^:]+::getNestedRuleSpec\(\) must be one of "dnSpecRule", "sepRule", or "controlRule", '.
//            '"foo" given\.$/'
//        );
//
//        $object->getNestedRuleSpec('foo');
//    }
//
//
//    public function test__initNestedRules__defaults()
//    {
//        $object = $this->getTestObject();
//
//        $this->assertSame($object, $object->initNestedRules());
//
//        $rules = $object->getNestedRules();
//
//        $this->assertIsArray($rules);
//        $this->assertCount(3, $rules);
//        $this->assertInstanceOf(DnSpecRule::class, $rules['dnSpecRule']);
//        $this->assertInstanceOf(SepRule::class, $rules['sepRule']);
//        $this->assertInstanceOf(ControlRule::class, $rules['controlRule']);
//
//        $this->assertSame(false, $rules['dnSpecRule']->isOptional());
//        $this->assertSame(false, $rules['sepRule']->isOptional());
//        $this->assertSame(true, $rules['controlRule']->isOptional());
//    }
//
//    public function test__initNestedRules__withUserRules()
//    {
//        $object = $this->getTestObject();
//
//        $dnSpecRule = new DnSpecRule;
//        $sepRule = new SepRule;
//        $controlRule = new ControlRule(true);
//
//        $this->assertSame($object, $object->initNestedRules([
//            'dnSpecRule' => $dnSpecRule,
//            'sepRule' => $sepRule,
//            'controlRule' => $controlRule,
//        ]));
//
//        $this->assertSame($dnSpecRule, $object->getNestedRule('dnSpecRule'));
//        $this->assertSame($sepRule, $object->getNestedRule('sepRule'));
//        $this->assertSame($controlRule, $object->getNestedRule('controlRule'));
//    }
}

// vim: syntax=php sw=4 ts=4 et:
