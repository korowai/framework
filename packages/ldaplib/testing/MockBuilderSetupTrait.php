<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MockBuilderSetupTrait
{
    abstract public function getBuilder(): MockBuilder;

    final public function onlyMethods(array $methods): self
    {
        $this->getBuilder()->onlyMethods($methods);
        return $this;
    }

    final public function addMethods(array $methods): self
    {
        $this->getBuilder()->addMethods($methods);
        return $this;
    }

    final public function setConstructorArgs(array $arguments): self
    {
        $this->getBuilder()->setConstructorArgs($arguments);
        return $this;
    }

    final public function setMockClassName(string $name): self
    {
        $this->getBuilder()->setMockClassName($name);
        return $this;
    }

    final public function disableOriginalConstructor(): self
    {
        $this->getBuilder()->disableOriginalConstructor();
        return $this;
    }

    final public function enableOriginalConstructor(): self
    {
        $this->getBuilder()->enableOriginalConstructor();
        return $this;
    }

    final public function disableOriginalClone(): self
    {
        $this->getBuilder()->disableOriginalClone();
        return $this;
    }

    final public function enableOriginalClone(): self
    {
        $this->getBuilder()->enableOriginalClone();
        return $this;
    }

    final public function disableAutoload(): self
    {
        $this->getBuilder()->disableAutoload();
        return $this;
    }

    final public function enableAutoload(): self
    {
        $this->getBuilder()->enableAutoload();
        return $this;
    }

    final public function disableArgumentCloning(): self
    {
        $this->getBuilder()->disableArgumentCloning();
        return $this;
    }

    final public function enableArgumentCloning(): self
    {
        $this->getBuilder()->enableArgumentCloning();
        return $this;
    }

    final public function disableProxyingToOriginalMethods(): self
    {
        $this->getBuilder()->disableProxyingToOriginalMethods();
        return $this;
    }

    final public function enableProxyingToOriginalMethods(): self
    {
        $this->getBuilder()->enableProxyingToOriginalMethods();
        return $this;
    }

    final public function setProxyTarget(object $object): self
    {
        $this->getBuilder()->setProxyTarget($object);
        return $this;
    }

    final public function allowMockingUnknownTypes(): self
    {
        $this->getBuilder()->allowMockingUnknownTypes();
        return $this;
    }

    final public function disallowMockingUnknownTypes(): self
    {
        $this->getBuilder()->disallowMockingUnknownTypes();
        return $this;
    }

    final public function disableAutoReturnValueGeneration(): self
    {
        $this->getBuilder()->disableAutoReturnValueGeneration();
        return $this;
    }

    final public function enableAutoReturnValueGeneration(): self
    {
        $this->getBuilder()->enableAutoReturnValueGeneration();
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
