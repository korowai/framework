<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Context
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

function with(ContextManagerInterface ... $context)
{
    return new WithContextCaller($context);
}

// vim: syntax=php sw=4 ts=4 et:
