<?php
/**
 * @file tests/Traits/ExposesRuleInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc\Traits;

use Korowai\Lib\Rfc\Traits\ExposesRuleInterface;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExposesRuleInterfaceTest extends TestCase
{
    public function getTestObject(RuleInterface $rule = null)
    {
        $obj = new class ($rule) implements RuleInterface {
            use ExposesRuleInterface;
            public function __construct(?RuleInterface $rule) { $this->rule = $rule; }
            public function getRfcRule() : ?RuleInterface { return $this->rule; }
        };
        return $obj;
    }

    public function test__name()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('name')
             ->with()
             ->willReturn('xyz');
        $obj = $this->getTestObject($rule);

        $this->assertSame('xyz', $obj->name());
    }

    public function test____toString()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('__toString')
             ->with()
             ->willReturn('foo');
        $obj = $this->getTestObject($rule);

        $this->assertSame('foo', $obj->__toString());
    }

    public function test__rule()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('rule')
             ->with()
             ->willReturn('/^foo$/');
        $obj = $this->getTestObject($rule);

        $this->assertSame('/^foo$/', $obj->rule());
    }

    public function test__captures()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('captures')
             ->with()
             ->willReturn(['v', 'e']);
        $obj = $this->getTestObject($rule);

        $this->assertSame(['v', 'e'], $obj->captures());
    }

    public function test__errorCaptures()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('errorCaptures')
             ->with()
             ->willReturn(['e']);
        $obj = $this->getTestObject($rule);

        $this->assertSame(['e'], $obj->errorCaptures());
    }

    public function test__valueCaptures()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('valueCaptures')
             ->with()
             ->willReturn(['v']);
        $obj = $this->getTestObject($rule);

        $this->assertSame(['v'], $obj->valueCaptures());
    }

    public function test__findCapturedErrors()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('findCapturedErrors')
             ->with(['v1', 'e1', 'v2', 'e2'])
             ->willReturn(['e1', 'e2']);
        $obj = $this->getTestObject($rule);

        $this->assertSame(['e1', 'e2'], $obj->findCapturedErrors(['v1', 'e1', 'v2', 'e2']));
    }

    public function test__findCapturedValues()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('findCapturedValues')
             ->with(['v1', 'e1', 'v2', 'e2'])
             ->willReturn(['v1', 'v2']);
        $obj = $this->getTestObject($rule);

        $this->assertSame(['v1', 'v2'], $obj->findCapturedValues(['v1', 'e1', 'v2', 'e2']));
    }

    public function test__getErrorMessage()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->exactly(2))
             ->method('getErrorMessage')
             ->withConsecutive([],['asd'])
             ->will($this->onConsecutiveCalls('foo', 'bar'));
        $obj = $this->getTestObject($rule);

        $this->assertSame('foo', $obj->getErrorMessage());
        $this->assertSame('bar', $obj->getErrorMessage('asd'));
    }

    public function test__isOptional()
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
                         ->getMockForAbstractClass();
        $rule->expects($this->once())
             ->method('isOptional')
             ->with()
             ->willReturn(true);
        $obj = $this->getTestObject($rule);

        $this->assertTrue($obj->isOptional());
    }
}

// vim: syntax=php sw=4 ts=4 et:
