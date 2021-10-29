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

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface MockBuilderInterface
{
    public function getMock(): MockObject;
    public function getMockForAbstractClass(): MockObject;
    public function getMockForTrait(): MockObject;
    public function onlyMethods(array $methods): MockBuilderInterface;
    public function addMethods(array $methods): MockBuilderInterface;
    public function setConstructorArgs(array $arguments): MockBuilderInterface;
    public function setMockClassName(string $name): MockBuilderInterface;
    public function disableOriginalConstructor(): MockBuilderInterface;
    public function enableOriginalConstructor(): MockBuilderInterface;
    public function disableOriginalClone(): MockBuilderInterface;
    public function enableOriginalClone(): MockBuilderInterface;
    public function disableAutoload(): MockBuilderInterface;
    public function enableAutoload(): MockBuilderInterface;
    public function disableArgumentCloning(): MockBuilderInterface;
    public function enableArgumentCloning(): MockBuilderInterface;
    public function disableProxyingToOriginalMethods(): MockBuilderInterface;
    public function enableProxyingToOriginalMethods(): MockBuilderInterface;
    public function setProxyTarget(object $object): MockBuilderInterface;
    public function allowMockingUnknownTypes(): MockBuilderInterface;
    public function disallowMockingUnknownTypes(): MockBuilderInterface;
    public function disableAutoReturnValueGeneration(): MockBuilderInterface;
    public function enableAutoReturnValueGeneration(): MockBuilderInterface;
}

// vim: syntax=php sw=4 ts=4 et:
