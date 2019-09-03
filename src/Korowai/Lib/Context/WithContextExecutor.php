<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ContextLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * Executes user code within a predefined context.
 */
class WithContextExecutor implements ExecutorInterface
{
    /**
     * @var ContextManagerInterface
     */
    protected $context;

    /**
     * Initializes the object
     *
     * @param ContextManagerInterface[] $context
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
    public function __invoke(callable $func)
    {
        $args = [];

        for($i = 0; $i < count($this->context); $i++) {
            $args[] = $this->context[$i]->enterContext();
        }

        $exception = null;
        $return = null;
        try {
            $return = call_user_func_array($func, $args);
        } catch(\Throwable $e) {
            $exception = $e;
        }

        for($i = count($this->context)-1; $i >= 0; $i--) {
            if($this->context[$i]->exitContext($exception)) {
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
