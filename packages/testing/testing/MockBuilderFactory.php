<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use PHPUnit\Framework\TestCase;

/**
 * Cretes instances of MockBuilderInterface.
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class MockBuilderFactory implements MockBuilderFactoryInterface
{
    /**
     * @var array<string,string>
     */
    private static $mixedOptions = [
        'onlyMethods' => 'onlyMethods',
        'addMethods' => 'addMethods',
        'constructorArgs' => 'setConstructorArgs',
        'mockClassName' => 'setMockClassName',
        'proxyTarget' => 'setProxyTarget',
    ];

    /**
     * @var array<string,array{0: string, 1:string}>
     */
    private static $boolOptions = [
        'originalConstructor' => ['enableOriginalConstructor', 'disableOriginalConstructor'],
        'originalClone' => ['enableOriginalClone', 'disableOriginalClone'],
        'autoload' => ['enableAutoload', 'disableAutoload'],
        'argumentCloning' => ['enableArgumentCloning', 'disableArgumentCloning'],
        'proxyingToOriginalMethods' => ['enableProxyingToOriginalMethods', 'disableProxyingToOriginalMethods'],
        'mockUnknownTypes' => ['allowMockingUnknownTypes', 'disallowMockingUnknownTypes'],
        'autoReturnValueGeneration' => ['enableAutoReturnValueGeneration', 'disableAutoReturnValueGeneration'],
    ];

    /**
     * @var TestCase
     */
    private $testCase;

    /**
     * Initializes the factory.
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * Creates an instance of MockBuilderInterface preconfigured with $config.
     */
    public function getMockBuilder(MockBuilderConfigInterface $config): MockBuilderInterface
    {
        $builder = new MockBuilder($this->testCase->getMockBuilder($config->mockedType()));
        $this->setupMockBuilder($builder, $config);

        return $builder;
    }

    private function setupMockBuilder(MockBuilderInterface $builder, MockBuilderConfigInterface $config): void
    {
        foreach (self::$mixedOptions as $getter => $setter) {
            $value = self::getOptionValue($config, $getter);
            self::handleMixedOption($builder, $setter, $value);
        }

        foreach (self::$boolOptions as $getter => $setters) {
            $value = self::getOptionValue($config, $getter);
            self::handleBoolOption($builder, $setters, $value);
        }
    }

    /**
     * @psalm-return mixed
     */
    private static function getOptionValue(MockBuilderConfigInterface $config, string $getter)
    {
        return call_user_func([$config, $getter]);
    }

    /**
     * @param mixed $value
     */
    private static function handleMixedOption(MockBuilderInterface $builder, string $setter, $value): void
    {
        if (null !== $value) {
            call_user_func([$builder, $setter], $value);
        }
    }

    private static function handleBoolOption(MockBuilderInterface $builder, array $setters, ?bool $flag): void
    {
        if (null !== $flag) {
            call_user_func([$builder, $setters[$flag ? 0 : 1]]);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
