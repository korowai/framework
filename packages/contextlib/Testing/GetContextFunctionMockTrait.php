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
trait GetContextFunctionMockTrait
{
    abstract public function getFunctionMock(string $namespace, string $function);

    private function getContextFunctionMock(string $name)
    {
        return $this->getFunctionMock('\Korowai\Lib\Context', $name);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
