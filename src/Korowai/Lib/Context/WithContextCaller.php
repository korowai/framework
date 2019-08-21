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

/**
 * Interface for context managers.
 */
class WithContextCaller
{
    /**
     * @var ContextManagerInterface
     */
    protected $context;

    /**
     * Initializes the context manager caller
     */
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    /**
     * Calls user function within context.
     *
     * @param callable $func The user function to be called
     * @return mixed The value returned by ``$func``.
     */
    public function call(callable $func)
    {
        $args = [];

        for($i = 0; $i < count($this->context); $i++) {
            $args[] = $this->context[$i]->__enter();
        }

        $exception = null;
        try {
            $return = call_user_func_array($func, $args);
        } catch(\Throwable $e) {
            $exception = $e;
        }

        for($i = count($this->context)-1; $i >= 0; $i--) {
            if($this->context[$i]->__exit($exception)) {
                $exception = null; // handled
            }
        }

        if(is_a($exception, \Throwable::class)) {
            throw $exception;
        }

        return $return;
    }
}

// vim: syntax=php sw=4 ts=4 et:
