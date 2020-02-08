<?php
/**
 * @file src/Rule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Rfc;

use Korowai\Lib\Rfc\Exception\InvalidRuleSetNameException;

/**
 * Single rule in the set of rules.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rule implements RuleInterface
{
    /**
     * @var string
     */
    protected $ruleSetClass;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * Initializes the rule.
     *
     * @param  string $ruleSetClass Must be a fully qualified name of a class implementing StaticRuleInterface.
     * @param  string $name Name of the rule in the $ruleSetClass.
     * @throws InvalidRuleSetNameException When invalid *$ruleSetClass* is passed to the constructor.
     */
    public function __construct(string $ruleSetClass, string $name)
    {
        if (!is_subclass_of($ruleSetClass, StaticRuleSetInterface::class)) {
            $message = 'Argument 1 passed to '.__class__.'::__construct() must be '.
                       'a name of class implementing '.StaticRuleSetInterface::class.', '.
                       '"'.$ruleSetClass.'" given';
            throw new InvalidRuleSetNameException($message);
        }
        $this->ruleSetClass = $ruleSetClass;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * Returns the *$ruleSetClass* passed in to __construc() at creation.
     *
     * @return string
     */
    public function ruleSetClass() : string
    {
        return $this->ruleSetClass;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->rule();
    }

    /**
     * {@inheritdoc}
     */
    public function rule() : string
    {
        return $this->delegate('rule', [$this->name()]);
    }

    /**
     * {@inheritdoc}
     */
    public function captures() : array
    {
        return $this->delegate('captures', [$this->name()]);
    }

    /**
     * {@inheritdoc}
     */
    public function errorCaptures() : array
    {
        return $this->delegate('errorCaptures', [$this->name()]);
    }

    /**
     * {@inheritdoc}
     */
    public function valueCaptures() : array
    {
        return $this->delegate('valueCaptures', [$this->name()]);
    }

    /**
     * {@inheritdoc}
     */
    public function findCapturedErrors(array $matches) : array
    {
        return $this->delegate('findCapturedErrors', [$this->name(), $matches]);
    }

    /**
     * {@inheritdoc}
     */
    public function findCapturedValues(array $matches) : array
    {
        return $this->delegate('findCapturedValues', [$this->name(), $matches]);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(string $errorKey) : string
    {
        return $this->delegate('getErrorMessage', [$errorKey, $this->name()]);
    }

    /**
     * Delegates method call to the $this->ruleSetClass.
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    protected function delegate(string $method, array $arguments)
    {
        return call_user_func_array([$this->ruleSetClass, $method], $arguments);
    }
}

// vim: syntax=php sw=4 ts=4 et:
