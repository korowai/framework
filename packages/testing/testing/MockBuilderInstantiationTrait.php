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
use PHPUnit\Framework\MockObject\MockObject;
use Korowai\Testing\MockBuilderInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MockBuilderInstantiationTrait
{
    abstract public function getMockBuilder(): MockBuilder;

    public function getMock(): MockObject
    {
        return $this->getMockBuilder()->getMock();
    }

    public function getMockForAbstractClass(): MockObject
    {
        return $this->getMockBuilder()->getMockForAbstractClass();
    }

    public function getMockForTrait(): MockObject
    {
        return $this->getMockBuilder()->getMockForTrait();
    }
}

// vim: syntax=php sw=4 ts=4 et:
