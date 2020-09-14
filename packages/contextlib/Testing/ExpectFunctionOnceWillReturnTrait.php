<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Contextlib;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExpectFunctionOnceWillReturnTrait
{
    abstract public function getContextFunctionMock(string $name);

    /**
     * @todo Write documentation.
     */
    public function expectFunctionOnceWillReturn(string $function, array $args, $return) : void
    {
        $this->getContextFunctionMock($function)
             ->expects($this->once())
             ->with(...$args)
             ->willReturn($return);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
