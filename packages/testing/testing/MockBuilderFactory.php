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
    private static $setValueOptions = [
        'onlyMethods' => 'onlyMethods',
        'addMethods' => 'addMethods',
        'constructorArgs' => 'setConstructorArgs',
        'mockClassName' => 'setMockClassName',
        'proxyTarget' => 'setProxyTarget'
    ];

    private static $enableDisableOptions = [
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
        foreach (self::$setValueOptions as $getter => $setter) {
            if (null !== ($value = call_user_func([$config, $getter]))) {
                call_user_func([$builder, $setter], $value);
            }
        }

        foreach (self::$enableDisableOptions as $getter => $setters) {
            if (null !== ($flag = call_user_func([$config, $getter]))) {
                call_user_func([$builder, $setters[$flag ? 0 : 1]]);
            }
        }
//
//        $this->setOption($builder, 'onlyMethods', $config->onlyMethods());
//        $this->setOption($builder, 'addMethods', $config->addMethods());
//        $this->setOption($builder, 'setConstructorArgs', $config->constructorArgs());
//        $this->setOption($builder, 'setMockClassName', $config->mockClassName());
//        $this->enableOrDisableOption(
//            $builder,
//            'enableOriginalConstructor',
//            'disableOriginalConstructor',
//            $config->originalConstructor()
//        );
//        $this->enableOrDisableOption(
//            $builder,
//            'enableOriginalClone',
//            'disableOriginalClone',
//            $config->originalClone()
//        );
//        $this->enableOrDisableOption(
//            $builder,
//            'enableAutoload',
//            'disableAutoload',
//            $config->autoload()
//        );
//        $this->enableOrDisableOption(
//            $builder,
//            'enableArgumentCloning',
//            'disableArgumentCloning',
//            $config->argumentCloning()
//        );
//        $this->enableOrDisableOption(
//            $builder,
//            'enableProxyingToOriginalMethods',
//            'disableProxyingToOriginalMethods',
//            $config->proxyingToOriginalMethods()
//        );
//        $this->setOption($builder, 'setProxyTarget', $config->proxyTarget());
//        $this->enableOrDisableOption(
//            $builder,
//            'allowMockingUnknownTypes',
//            'disallowMockingUnknownTypes',
//            $config->mockUnknownTypes()
//        );
//        $this->enableOrDisableOption(
//            $builder,
//            'enableAutoReturnValueGeneration',
//            'disableAutoReturnValueGeneration',
//            $config->autoReturnValueGeneration()
//        );
    }
//
//    private function setOption(MockBuilderInterface $builder, string $setter,$value): void
//    {
//        if (null !== $value) {
//            call_user_func([$builder, $setter], $value);
//        }
//    }
//
//    private function enableOrDisableOption(
//        MockBuilderInterface $builder,
//        string $enabler,
//        string $disabler,
//        ?bool $value
//    ): void {
//        if (null === $value) {
//            return;
//        }
//
//        if ($value) {
//            call_user_func([$builder, $enabler]);
//        } else {
//            call_user_func([$builder, $disabler]);
//        }
//    }
}

// vim: syntax=php sw=4 ts=4 et:
