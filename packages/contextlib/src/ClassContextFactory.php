<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * A factory that associates classes with context managers.
 */
final class ClassContextFactory extends AbstractManagedContextFactory
{
    /**
     * @var array
     */
    protected $registry;

    /**
     * Initializes the object.
     */
    public function __construct(array $wrappers = [])
    {
        $this->initialize($wrappers);
    }

    /**
     * Returns the internal registry which maps class names into their
     * corresponding context manager generators.
     */
    public function getRegistry(): array
    {
        return $this->registry;
    }

    /**
     * Register new class.
     *
     * @param mixed $contextManager
     *
     * @return ClassContextFactory $this
     */
    public function register(string $class, $contextManager): ClassContextFactory
    {
        if (is_callable($contextManager)) {
            $wrapper = $contextManager;
        } elseif (is_string($contextManager) && class_exists($contextManager)) {
            $wrapper = function ($arg) use ($contextManager) {
                return new $contextManager($arg);
            };
        } else {
            throw new \InvalidArgumentException(
                'argument 2 to '.__METHOD__.'() must be a callable or a'.
                ' class name, '.gettype($contextManager).' given'
            );
        }
        $this->registry[ltrim($class, '\\')] = $wrapper;

        return $this;
    }

    /**
     * Unregister the $class.
     *
     * @return ClassContextFactory $this
     */
    public function remove(string $class): ClassContextFactory
    {
        unset($this->registry[ltrim($class, '\\')]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextManager($arg): ?ContextManagerInterface
    {
        if (is_object($arg)) {
            $class = get_class($arg);
            if (null !== ($wrapper = $this->registry[$class] ?? null)) {
                return call_user_func($wrapper, $arg);
            }
        }

        return null;
    }

    /**
     * Initializes the object with $wrappers.
     */
    protected function initialize(array $wrappers)
    {
        $this->registry = [];

        foreach ($wrappers as $key => $val) {
            $this->register($key, $val);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
