<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Contextlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

/**
 * A trivial context manager which only wraps a single value.
 *
 * The __enter() method returns the ``$value`` passed as argument to
 * ``__construct()``, while __exit() returns false.
 */
class TrivialValueWrapper implements ContextManagerInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Initializes the object.
     *
     * @param mixed $value The value being wrapped by the object.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function __enter()
    {
        return $this->value;
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
