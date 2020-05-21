<?php
/**
 * @file src/Rules/LdifChangeRecordRule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Ldif\Traits\LdifChangeRecordNestedRules;

/**
 * A rule object that parses *ldif-change-record* rule defined in Rfc2849.
 *
 * - semantic value: [ChangeRecordInterface](\.\./ChangeRecordInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdifChangeRecordRule implements RuleInterface
{
    use LdifChangeRecordNestedRules;

    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     * @param  array $options
     */
    public function __construct(bool $tryOnly = false, array $options = [])
    {
        $rules = array_intersect_key($options, static::getNestedRulesSpecs());
        if (($dnSpecRule = ($rules['dnSpecRule'] ?? null)) === null) {
            $rules['dnSpecRule'] = new DnSpecRule($tryOnly);
        } elseif ($tryOnly !== $dnSpecRule) {
            $call = __class__.'::__construct($tryOnly, $options)';
            $message = 'Argument $tryOnly in '.$call.' must be consistent with $options["dnSpecRule"], '.
                       '$tryOnly === $options["dnSpecRule"]->isOptional() must hold.';
            // FIXME: dedicated exception
            throw new \InvalidArgumentException($message);
        }
        $this->initNestedRules($rules);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional() : bool
    {
        return $this->getDnSpecRule()->isOptional();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state, &$value = null) : bool
    {
        $cursor = $state->getCursor();
        $begin = $cursor->getClonedLocation();

        if (!$this->getDnSpecRule()->parse($state, $dn) || !$this->getSepRule()->parse($state)) {
            return false;
        }

        $initErrCount = count($state->getErrors());
        $controls = Util::repeat($this->getControlRule(), $state);
        if (count($state->getErrors()) > $initErrCount) {
            return false;
        }

        return $this->parseChangeRecord($state, $dn, $value);
    }

    /**
     * @todo Write documentation
     */
    public function parseChangeRecord(State $state, string $dn, &$value = null) : bool
    {
        static $parsers = [
            'add'    => 'parseAdd',
            'delete' => 'parseDelete',
            'moddn'  => 'parseModDn',
            'modrdn' => 'parseModRdn',
            'modify' => 'parseModify',
        ];

        if (!$this->getChangeRecordInitRule($state, $changeType)) {
            return false;
        }

        if (($parser = $parsers[$changeType] ?? null) === null) {
            $state->errorHere('internal error: unsupported changeType: "'.$changeType.'"');
            $value = null;
            return false;
        }

        return $this->{$parser}($state, $dn, $value);
    }

    /**
     * @todo Write documentation
     */
    public function parseAdd(State $state, string $dn, &$value = null)
    {
        throw new \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseDelete(State $state, string $dn, &$value = null)
    {
        throw new \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseModDn(State $state, string $dn, &$value = null)
    {
        throw new \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseModRdn(State $state, string $dn, &$value = null)
    {
        throw new \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseModify(State $state, string $dn, &$value = null)
    {
    }
}
// vim: syntax=php sw=4 ts=4 et:
