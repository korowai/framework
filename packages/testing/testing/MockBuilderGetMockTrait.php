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

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @psalm-template MockedType
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MockBuilderGetMockTrait
{
    abstract public function getMockBuilder(): MockBuilder;

    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     *
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\ClassAlreadyExistsException
     * @throws \PHPUnit\Framework\MockObject\ClassIsEnumerationException
     * @throws \PHPUnit\Framework\MockObject\ClassIsFinalException
     * @throws \PHPUnit\Framework\MockObject\DuplicateMethodException
     * @throws \PHPUnit\Framework\MockObject\InvalidMethodNameException
     * @throws \PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \PHPUnit\Framework\MockObject\UnknownTypeException
     */
    public function getMock(): MockObject
    {
        return $this->getMockBuilder()->getMock();
    }

    /**
     * Creates a mock object for an abstract class.
     *
     * @psalm-return MockObject&MockedType
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function getMockForAbstractClass(): MockObject
    {
        return $this->getMockBuilder()->getMockForAbstractClass();
    }

    /**
     * Creates a mock object for a trait.
     *
     * @psalm-return MockObject&MockedType
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function getMockForTrait(): MockObject
    {
        return $this->getMockBuilder()->getMockForTrait();
    }
}

// vim: syntax=php sw=4 ts=4 et:
