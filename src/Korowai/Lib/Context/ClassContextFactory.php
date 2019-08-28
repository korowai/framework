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
 * A factory that associates classes with context managers.
 */
class ClassContextFactory extends AbstractContextFactory
{
    /**
     * @var array
     */
    protected $wrappers;


    /**
     * Initializes the object
     *
     * @param array $wrappers
     */
    public function __construct(array $wrappers = [])
    {
        $this->initialize($wrappers);
    }

    protected function initialize(array $wrappers)
    {
        $this->wrappers = [];

        foreach($wrappers as $key => $val) {
            $this->register($key, $val);
        }
    }

    /**
     * Register new class.
     *
     * @param string $class
     * @param mixed $contextManager
     *
     * @return ClassContextFactory $this
     */
    public function register(string $class, $contextManager) : ClassContextFactory
    {
        if(is_callable($contextManager)) {
            $wrapper = $contextManager;
        } elseif(class_exists($contextManager)) {
            $wrapper = function($arg) use ($contextManager) {
                return new $contextManager($arg);
            };
        } else {
            $msg = "argument 2 to ClassContextFactory::register() ".
                   "must be a callable or a class name";
            throw new \InvalidArgumentException($msg);
        }
        $this->wrappers[ltrim($class, '\\')] = $wrapper;
        return $this;
    }

    /**
     * Unregister the $class.
     *
     * @param string $class
     * @return ClassContextFactory $this
     */
    public function remove(string $class) : ClassContextFactory
    {
        unset($this->wrappers[$class]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextManager($arg) : ?ContextManagerInterface
    {
        if(is_object($arg)) {
            $class = get_class($arg);
            if(null !== ($wrapper = $this->wrappers[$class] ?? null)) {
                return call_user_func($wrapper, $arg);
            }
        }
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
