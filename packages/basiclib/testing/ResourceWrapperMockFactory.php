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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class ResourceWrapperMockFactory extends TypedMockFactory
{
    private $resource;

    private $isResourceValid = false;

    private $supportedResourceTypes = [];

    final public function setResource($resource): ResourceWrapperMockFactory
    {
        $this->resource = $resource;

        return $this;
    }

    final public function getResource()
    {
        return $this->resource;
    }

    final public function setIsResourceValid(bool $isResourceValid): ResourceWrapperMockFactory
    {
        $this->isResourceValid = $isResourceValid;

        return $this;
    }

    final public function getIsResourceValid(): bool
    {
        return $this->isResourceValid;
    }

    final public function setSupportedResourceTypes(array $supportedResourceTypes): ResourceWrapperMockFactory
    {
        $this->supportedResourceTypes = $supportedResourceTypes;

        return $this;
    }

    final public function getSupportedResourceTypes(): array
    {
        return $this->supportedResourceTypes;
    }

    protected function setupMockBuilderMethods(): void
    {
        $onlyMethods = [];

        if (null !== $this->getResource()) {
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
