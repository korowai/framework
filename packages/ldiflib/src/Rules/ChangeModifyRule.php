<?php
/**
 * @file src/Rules/ChangeModifyRule.php
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
use Korowai\Lib\Ldif\Scan;
use Korowai\Lib\Ldif\ModSpec;
use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\Records\ModifyRecord;
use Korowai\Lib\Rfc\Rfc2849x;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ChangeModifyRule implements RuleInterface
{
    /**
     * @var ModSpecRule
     */
    private $modSpecRule;

    /**
     * @var bool
     */
    private $optional;

    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     * @param  ModSpecRule $modSpecRule
     */
    public function __construct(bool $tryOnly = false, ModSpecRule $modSpecRule = null)
    {
        $this->optional = $tryOnly;
        if ($modSpecRule === null) {
            $modSpecRule = new ModSpecRule(true);
        }
        $this->setModSpecRule($modSpecRule);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional() : bool
    {
        return $this->optional;
    }

    /**
     * Returns the [ModSpecRule](ModSpecRule.html) set to this object.
     *
     * @return ModSpecRule
     */
    public function getModSpecRule() : ModSpecRule
    {
        return $this->modSpecRule;
    }

    /**
     * Sets new instance of [ModSpecRule](ModSpecRule.html) to this object.
     *
     * @param  ModSpecRule $modSpecRule
     * @return object $this
     */
    public function setModSpecRule(ModSpecRule $modSpecRule)
    {
        if (!$modSpecRule->isOptional()) {
            // FIXME: dedicated exception
            $message = 'Argument 1 to '.__class__.'::setModSpecRule() must satisfy $1->isOptional() === true.';
            throw new \InvalidArgumentException($message);
        }
        $this->modSpecRule = $modSpecRule;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state, &$value = null) : bool
    {
        return $this->parseModifyTag($state) && $this->parseModSpecs($state, $value);
    }

    /**
     * @todoWrite documentation
     */
    protected function parseModSpecs(State $state, array &$modSpecs = null)
    {
        $initErrCount = count($state->getErrors());
        $modSpecs = Util::repeat($this->getModSpecRule(), $state);
        return !(count($state->getErrors()) > $initErrCount);
    }

    /**
     * @todo Write documentation
     */
    protected function parseModifyTag(State $state)
    {
        $cursor = $state->getCursor();
        if (!Scan::matchAhead('/\Gmodify'.Rfc2849x::SEP_X.'/D', $cursor, PREG_UNMATCHED_AS_NULL)) {
            if (!$this->isOptional()) {
                $state->errorHere('syntax error: expected "modify" keyword followed by end of line');
            }
            return false;
        }
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
