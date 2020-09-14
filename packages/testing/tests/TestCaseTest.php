<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use Korowai\Testing\TestCase;
use Korowai\Testing\Assertions\ClassAssertionsTrait;
use Korowai\Testing\Assertions\ObjectPropertiesAssertionsTrait;
use Korowai\Testing\Assertions\PregAssertionsTrait;
use Korowai\Testing\Traits\ObjectPropertiesUtilsTrait;
use Korowai\Testing\Traits\PregUtilsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function test__uses__ClassAssertionsTrait()
    {
        $this->assertUsesTrait(ClassAssertionsTrait::class, TestCase::class);
    }

    public function test__uses__ObjectPropertiesAssertionsTrait()
    {
        $this->assertUsesTrait(ObjectPropertiesAssertionsTrait::class, TestCase::class);
    }

    public function test__uses__PregAssertionsTrait()
    {
        $this->assertUsesTrait(PregAssertionsTrait::class, TestCase::class);
    }

    public function test__uses__ObjectPropertiesUtilsTrait()
    {
        $this->assertUsesTrait(ObjectPropertiesUtilsTrait::class, TestCase::class);
    }

    public function test__uses__PregUtilsTrait()
    {
        $this->assertUsesTrait(PregUtilsTrait::class, TestCase::class);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            \Iterator::class => [
                'current'                   => 'current',
                'key'                       => 'key',
                'valid'                     => 'valid',
            ],

            \IteratorAggregate::class => [
                'iterator'                  => 'getIterator'
            ],

            \Throwable::class => [
                'message'                   => 'getMessage',
                'code'                      => 'getCode',
                'file'                      => 'getFile',
                'line'                      => 'getLine',
                'trace'                     => 'getTrace',
                'traceAsString'             => 'getTraceAsString',
                'previous'                  => 'getPrevious',
            ],
        ];
        $this->assertSame($expect, TestCase::objectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
