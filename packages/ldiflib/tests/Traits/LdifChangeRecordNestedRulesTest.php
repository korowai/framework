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
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangeRecordNestedRulesTest extends TestCase
{
    public function test__getNestedRulesSpecs()
    {
        $object = $this->getMockForTrait(LdifChangeRecordNestedRules::class);

        $this->assertSame([
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
            ],
            get_class($object)::getNestedRulesSpecs()
        );
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

}

// vim: syntax=php sw=4 ts=4 et:
