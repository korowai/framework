<?php
/**
 * @file tests/Traits/HasParserRulesTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\HasParserRules;
use Korowai\Lib\Ldif\Exception\InvalidRuleNameException;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\ValueSpecRule;
use Korowai\Lib\Ldif\Rules\VersionSpecRule;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class HasParserRulesTest extends TestCase
{
    public function getTestObject()
    {
        return new class { use HasParserRules {getParserRule as public;} };
    }

    public static function ruleGetter__cases()
    {
        return [
            ['attrValSpecRule', AttrValSpecRule::class],
            ['controlRule', ControlRule::class],
            ['dnSpecRule', DnSpecRule::class],
            ['sepRule', SepRule::class],
            ['valueSpecRule', ValueSpecRule::class],
            ['versionSpecRule', VersionSpecRule::class],
        ];
    }

    /**
     * @dataProvider ruleGetter__cases
     */
    public function test__ruleGetter(string $getter, string $class)
    {
        $object = $this->getTestObject();

        $rules = [
            'd' => [$object->{$getter}(),         $object->{$getter}()],
            'f' => [$object->{$getter}(false),    $object->{$getter}(false)],
            't' => [$object->{$getter}(true),     $object->{$getter}(true)]
        ];

        $this->assertInstanceOf($class, $rules['d'][0]);
        $this->assertInstanceOf($class, $rules['d'][1]);
        $this->assertInstanceOf($class, $rules['f'][0]);
        $this->assertInstanceOf($class, $rules['f'][1]);
        $this->assertInstanceOf($class, $rules['t'][0]);
        $this->assertInstanceOf($class, $rules['t'][1]);

        $this->assertSame($rules['d'][0], $rules['d'][1]);
        $this->assertSame($rules['f'][0], $rules['f'][1]);
        $this->assertSame($rules['t'][0], $rules['t'][1]);
        $this->assertSame($rules['d'][0], $rules['f'][0]);
        $this->assertNotSame($rules['f'][0], $rules['t'][0]);
        $this->assertNotSame($rules['t'][0], $rules['d'][0]);

        $this->assertFalse($rules['d'][0]->isOptional());
        $this->assertTrue($rules['t'][0]->isOptional());
        $this->assertFalse($rules['f'][0]->isOptional());
    }

    public function test__getParserRule__withWrongRuleClass()
    {
        $object = $this->getTestObject();
        $regexp = '/Argument 1 passed to .+::getParserRule\(\) must be a name of class implementing '.
                  preg_quote(RuleInterface::class).', "foo" given/';
        $this->expectException(InvalidRuleNameException::class);
        $this->expectExceptionMessageMatches($regexp);

        $object->getParserRule('foo');
    }
}

// vim: syntax=php sw=4 ts=4 et:
