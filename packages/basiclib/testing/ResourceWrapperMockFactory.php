<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Basiclib;

use Korowai\Testing\TypedMockFactory;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class ResourceWrapperMockFactory extends TypedMockFactory
{
//    protected $methodConfigOptions = [
//        'getResource' => [ 'return' => null ],
//        'isValid' => [ 'return' => false ],
//        'supportsResourceType' => ['types' => [] ],
//    ];
//
//    /**
//     * @var array<string,bool>
//     */
//    protected $methodConfigEnabled = [
//        'getResource' => true,
//        'isValid' => true,
//        'supportsResourceType' => true,
//    ];
//
//    /**
//     * @psalm-return $this
//     */
//    final public function setResource($resource): ResourceWrapperMockBuilderInterface
//    {
//        $this->methodConfigOptions['getResouce']['return'] = $resource;
//
//        return $this;
//    }
//
//    /**
//     * @psalm-return $this;
//     */
//    final public function setResourceIsValid(bool $isValid): ResourceWrapperMockBuilderInterface
//    {
//        $this->methodConfigOptions['isValid']['return'] = $isValid;
//        return $this;
//    }
//
//    final public function setSupportedResourceTypes(array $types): ResourceWrapperMockBuilderInterface
//    {
//        $this->methodConfigOptions['supportsResourceType']['types'] = $types;
//        return $this;
//    }
//
//    /**
//     * Disables automatic configuration of the ``$method`` on a mock object
//     * being returned.
//     *
//     * @psalm-return $this
//     */
//    final public function disableMethodConfig(string $method): ResourceWrapperMockBuilderInterface
//    {
//        $this->methodConfigEnabled[$method] = false;
//        return $this;
//    }
//
//    /**
//     * Enables automatic configuration of the ``$method`` on a mock object
//     * being returned.
//     *
//     * @psalm-return $this
//     */
//    final public function enableMethodConfig(string $method): ResourceWrapperMockBuilderInterface
//    {
//        $this->methodConfigEnabled[$method] = false;
//        return $this;
//    }
//
//    /**
//     * Returns whether the config for given method is enabled.
//     */
//    final public function isMethodConfigEnabled(string $method): bool
//    {
//        return $this->methodConfigEnabled[$method] ?? false;
//    }
//
//    protected function setupMockBuilder(TestCase $testCase, MockBuilder $builder): void
//    {
//        parent::setupMockBuilder($testCase, $builder);
//    }
//
//    protected function configureMock(TestCase $testCase, MockObject $mock): void
//    {
//        $this->configureResourceWrapperMethods($testCase, $mock);
//        parent::configureMock($testCase, $mock);
//    }
//
//    protected function configureMockedMethods(TestCase $testCase, MockObject $mock): void
//    {
//        $this->configureMockedResourceWrapperMethods($testCase, $mock);
//    }
//
//    protected function configureMockedResourceWrapperMethods(TestCase $testCase, MockObject $mock): void
//    {
//        if ($this->isMethodConfigEnabled('getResource')) {
//            $mock->expects($testCase->any())
//                 -> method('getResource')
//                 ->willReturn($this->methodConfigOptions['getResource']['return']);
//        }
//
//        if ($this->isMethodConfigEnabled(['isValid'])) {
//            $mock->expects($testCase->any())
//                 ->method('isValid')
//                 ->willReturn($this->methodConfigOptions['isValid']['return']);
//        }
//
//        if ($this->isMethodConfigEnabled('supportsResourceType')) {
//            $mock->expects($testCase->any())
//                 ->method('supportsResourceType')
//                 ->willReturnCallback(function (string $type): bool use ($this) {
//                     return in_array($type, $this->methodConfigOptions['supportsResourceType']['types']);
//                 });
//        }
//    }
}

// vim: syntax=php sw=4 ts=4 et:
