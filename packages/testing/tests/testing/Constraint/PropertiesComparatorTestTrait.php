<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Constraint;

use Korowai\Testing\Constraint\AbstractPropertiesComparator;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapper;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PropertiesComparatorTestTrait
{
    abstract public function getPropertiesComparatorClass(): string;

    abstract public static function assertExtendsClass(string $parent, $object, string $message = ''): void;

    abstract public static function assertImplementsInterface(string $interface, $object, string $message = ''): void;

    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    abstract public function createMock(string $name);

    public function testExtendsAbstractPropertiesComparator(): void
    {
        $class = $this->getPropertiesComparatorClass();
        $this->assertExtendsClass(AbstractPropertiesComparator::class, $class);
    }

    public function testImplementsExpectedPropertiesInterface(): void
    {
        $class = $this->getPropertiesComparatorClass();
        $this->assertImplementsInterface(ExpectedPropertiesInterface::class, $class);
    }

    // @codeCoverageIgnoreStart
    public function provFromArray(): array
    {
        $unwrapper = $this->createMock(RecursivePropertiesUnwrapperInterface::class);

        return [
            'PropertiesComparatorTestTrait.php:'.__LINE__ => [
                'args' => [['foo' => 'FOO']],
                'expect' => [
                    'properties' => ['foo' => 'FOO'],
                    'unwrapper' => static::isInstanceOf(RecursivePropertiesUnwrapper::class),
                ],
            ],

            'PropertiesComparatorTestTrait.php:'.__LINE__ => [
                'args' => [['foo' => 'FOO'], $unwrapper],
                'expect' => [
                    'properties' => ['foo' => 'FOO'],
                    'unwrapper' => static::identicalTo($unwrapper),
                ],
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provFromArray
     */
    public function testFromArray(array $args, array $expect): void
    {
        $class = $this->getPropertiesComparatorClass();
        $constraint = $class::fromArray(...$args);
        $this->assertThat($constraint->getPropertiesUnwrapper(), $expect['unwrapper']);
        $this->assertSame($expect['properties'], $constraint->getArrayCopy());
    }
}

// vim: syntax=php sw=4 ts=4 et:
