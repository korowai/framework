<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Contextlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * A base class for classes that implement __enter() and __exit(). A
 * default implementation for __enter() is provided which returns ``$this``
 * while __exit() by default returns false.
 */
class DefaultContextManager implements ContextManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __enter()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __exit(?\Throwable $exception) : bool
    {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
