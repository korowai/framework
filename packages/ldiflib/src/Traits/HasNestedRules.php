<?php
/**
 * @file src/Traits/HasNestedRules.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;
use Korowai\Lib\Ldif\Exception\InvalidRuleNameException;
use Korowai\Lib\Ldif\Exception\NoRulesDefinedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait HasNestedRules
{
    /**
     * Returns an array of nested rules' specifications for this class.
     *
     * @return array
     *      Returns array of key => value pairs, where keys are nested rules'
     *      identifiers, unique within the class, and values are arrays of the
     *      following key => value specifications:
     *
     * - ``"class" => string`` (required): name of the class implementing given rule, the
     *   class itself must implement [RuleInterface](\.\./RuleInterface.html),
     * - ``"construct" => array`` (optional): if set, provides argument values to
     *   be passed to rule's constructor when creating the rule during default
     *   initialization,
     * - ``"optional" => bool`` (optional): if set, then the class ensures that
     *   the given nested *$rule* satisfies ``$rule->isOptional() === $optional``.
     */
    abstract public static function getNestedRulesSpecs() : array;

    /**
     * @var RuleInterface[]
     */
    private $nestedRules = [];

    /**
     * Returns object's nested rules.
     *
     * @return array
     *      Returns array of RuleInterface instances.
     */
    public function getNestedRules() : array
    {
        return $this->nestedRules;
    }

    /**
     * Returns object's nested rule identified by *$key*.
     *
     * @param  string $key
     *      Nested rule identifier.
     * @return RuleInterface
     * @throws InvalidRuleNameException
     *      When *$key* does not name an initialized nested rule.
     * @throws NoRulesDefinedException
     *      When ``getNestedRules()`` returns empty array.
     */
    public function getNestedRule(string $key) : RuleInterface
    {
        $rules = $this->getNestedRules();
        static::checkNestedRuleKey(
            $key,
            $rules,
            'Argument 1 to '.__class__.'::'.__function__.'()',
            __class__.'::'.__function__.'() can\'t be used, no nested rules set.'
        );
        return $rules[$key];
    }

    /**
     * Sets given nested rule to the object.
     *
     * @param  string $key
     *      Nested rule identifier,
     * @param  RuleInterface $rule
     *      Rule object to be assigned.
     * @return object $this
     * @throws InvalidRuleNameException
     *      When *$key* is not a supported nested rule identifier.
     * @throws NoRulesDefinedException
     *      When ``getNestedRulesSpecs()`` returns empty array.
     * @throws InvalidRuleClassException
     *      When *$rule*'s actual class does not meet rule's specification.
     */
    public function setNestedRule(string $key, RuleInterface $rule)
    {
        $specs = static::getNestedRulesSpecs();
        static::checkNestedRuleKey(
            $key,
            $specs,
            'Argument 1 to '.__class__.'::'.__function__.'()',
            __class__.'::'.__function__.'() can\'t be used, no nested rules defined.'
        );
        $spec = $specs[$key];

        $class = $spec['class'];
        if (!($rule instanceof $class)) {
            $call = __class__.'::setNestedRule("'.addslashes($key).'", $rule)';
            $message = 'Argument $rule in '.$call.' must be an '.
                'instance of '.$class.', instance of '.get_class($rule).' given.';
            throw new InvalidRuleClassException($message);
        }

        $optional = $spec['optional'] ?? null;
        if ($optional !== null && $rule->isOptional() !== $optional) {
            $call = __class__.'::setNestedRule("'.addslashes($key).'", $rule)';
            $expected = $optional ? 'true' : 'false';
            $message = 'Argument $rule in '.$call.' must satisfy $rule->isOptional() === '.$expected.'.';
            // FIXME: dedicated exception
            throw new \InvalidArgumentException($message);
        }

        $this->nestedRules[$key] = $rule;
        return $this;
    }

    /**
     * Returns specification for nested rule identified by *$key*.
     *
     * @param  string $key
     * @return array
     * @throws InvalidRuleNameException When *$key* is not a supported nested rule name.
     * @throws NoRulesDefinedException When ``getNestedRulesSpecs()`` returns empty array.
     */
    public static function getNestedRuleSpec(string $key) : array
    {
        $specs = static::getNestedRulesSpecs();
        static::checkNestedRuleKey(
            $key,
            $specs,
            'Argument 1 to '.__class__.'::'.__function__.'()',
            __class__.'::'.__function__.'() can\'t be used, no nested rules defined.'
        );
        return $specs[$key];
    }

    /**
     * Initializes all nested rules.
     *
     * @param  array $rules
     * @return object $this
     */
    protected function initNestedRules(array $rules = [])
    {
        foreach (array_keys(static::getNestedRulesSpecs()) as $key) {
            $this->initNestedRule($key, $rules[$key] ?? null);
        }
        return $this;
    }

    /**
     * Initializes nested rule identified by *$key*. If *$rule* is missing or
     * null, default instance is created automatically.
     *
     * @param  string $key
     * @param  RuleInterface $rule
     * @return object $this
     */
    protected function initNestedRule(string $key, RuleInterface $rule = null)
    {
        if ($rule === null) {
            $spec = static::getNestedRuleSpec($key);
            $class = $spec['class'];
            $args = $spec['construct'] ?? [];
            $rule = new $class(...$args);
        }
        $this->setNestedRule($key, $rule);
        return $this;
    }

    /**
     * Throws exception if *$array* is empty or *$key* is not in *$array*.
     *
     * @param  string $key
     *      The key to be examined.
     * @param  array $array
     *      The array to examine *$key* against.
     * @param  string $keyinfo
     *      A string used to build up the message for InvalidRuleNameException
     *      (when key is missing). The full message sentence is like
     *      "$keyinfo must be ...". The *$keyinfo* provides initial phrase such
     *      as "Argument 1 to Foo::bar()".
     * @param  string $emtpy
     *      A message for NoRulesDefinedException.
     * @throws NoRulesDefinedException
     *      When *$array* is empty.
     * @throws InvalidRuleNameException
     *      When *$key* is not a key from *$array*.
     */
    protected static function checkNestedRuleKey(string $key, array $array, string $keyinfo, string $emtpy)
    {
        if (empty($array)) {
            throw new NoRulesDefinedException($emtpy);
        } elseif (!array_key_exists($key, $array)) {
            $alts = static::formatQuotedAlternatives(array_keys($array));
            $message = $keyinfo.' must be '.$alts.', "'.addslashes($key).'" given.';
            throw new InvalidRuleNameException($message);
        }
    }

    /**
     * Returns a string that enumerates alternatives provided by *$strings*
     * array. The returned alternatives are enclosed with double quotes and
     * escaped with ``addslashes()``.
     *
     * @param  array $strings
     * @return string
     */
    protected static function formatQuotedAlternatives(array $strings) : string
    {
        $quoted = array_map(function (string $s) {
            return '"'.addslashes($s).'"';
        }, $strings);
        return static::formatAlternatives($quoted);
    }

    /**
     * Returns a string that enumerates alternatives provided by *$strings* array.
     *
     * @param  array $strings
     * @return string
     */
    protected static function formatAlternatives(array $strings) : string
    {
        switch (count($strings)) {
            case 0:
                $string = '<no choices>';
                break;
            case 1:
                $string = 'exactly '.$strings[0];
                break;
            case 2:
                $string = 'either '.$strings[0].' or '.$strings[1];
                break;
            default:
                $head = implode(', ', array_slice($strings, 0, -1));
                $last = $strings[count($strings)-1];
                $string = 'one of '.$head.', or '.$last;
                break;
        }

        return $string;
    }
}

// vim: syntax=php sw=4 ts=4 et:
