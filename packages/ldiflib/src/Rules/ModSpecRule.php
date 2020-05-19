<?php
/**
 * @file src/Rules/ModSpecRule.php
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
use Korowai\Lib\Rfc\Rfc2849x;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModSpecRule implements RuleInterface
{

    /**
     * @var ModSpecInitRule
     */
    public $modSpecInitRule;

    /**
     * @var AttrValSpecRule
     */
    public $attrValSpecRule;

    /**
     * Initializes the object.
     *
     * @param  bool $tryOnly
     * @param  ModSpecInitRule $modSpecInitRule
     * @param  AttrValSpecRule $attrValSpecRule
     */
    public function __construct(
        bool $tryOnly = false,
        ModSpecInitRule $modSpecInitRule = null,
        AttrValSpecRule $attrValSpecRule = null
    ) {
        if ($modSpecInitRule === null) {
            $modSpecInitRule = new ModSpecInitRule($tryOnly);
        } elseif ($modSpecInitRule->isOptional() !== $tryOnly) {
            $message = 'Argument 1 to '.__class__.'::__construct() must be consistent with argument 2, '.
                       'however the condition $1 === $2->isOptional() is not satisfied.';
            // FIXME: dedicated exception
            throw new \InvalidArgumentException($message);
        }

        if ($attrValSpecRule === null) {
            $attrValSpecRule = new AttrValSpecRule(true);
        }

        $this->setModSpecInitRule($modSpecInitRule);
        $this->setAttrValSpecRule($attrValSpecRule);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional() : bool
    {
        return $this->getModSpecInitRule()->isOptional();
    }

    /**
     * Sets new instance of [ModSpecInitRule](ModSpecInitRule.html).
     *
     * @param  ModSpecInitRule $modSpecInitRule
     * @return object $this
     */
    public function setModSpecInitRule(ModSpecInitRule $modSpecInitRule)
    {
        $this->modSpecInitRule = $modSpecInitRule;
        return $this;
    }

    /**
     * Returns the instance of [ModSpecInitRule](ModSpecInitRule.html)
     *
     * @return ModSpecInitRule
     */
    public function getModSpecInitRule() : ModSpecInitRule
    {
        return $this->modSpecInitRule;
    }

    /**
     * Sets new instance of [AttrValSpecRule](AttrValSpecRule.html).
     *
     * @param  AttrValSpecRule $attrValSpecRule
     * @return object $this
     */
    public function setAttrValSpecRule(AttrValSpecRule $attrValSpecRule)
    {
        if (!$attrValSpecRule->isOptional()) {
            // FIXME: dedicated exception
            $message = 'Argument 1 to '.__class__.'::setAttrValSpecRule() must satisfy $1->isOptional() === true.';
            throw new \InvalidArgumentException($message);
        }
        $this->attrValSpecRule = $attrValSpecRule;
        return $this;
    }

    /**
     * Returns the instance of [AttrValSpecRule](AttrValSpecRule.html).
     *
     * @return AttrValSpecRule
     */
    public function getAttrValSpecRule() : AttrValSpecRule
    {
        return $this->attrValSpecRule;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state, &$value = null) : bool
    {
        if (!$this->getModSpecInitRule()->parse($state, $value) || !$this->parseAttrValSpecs($state, $value)) {
            return false;
        }
        return $this->parseEndMarker($state);
    }

    /**
     * Parses zero or more *attrval-spec*s.
     *
     * @param  State $state
     * @param  ModSpec $modSpec
     */
    protected function parseAttrValSpecs(State $state, ModSpec $modSpec)
    {
        $initErrCount = count($state->getErrors());
        $attrVals = Util::repeat($this->getAttrValSpecRule(), $state);
        $modSpec->setAttrValSpecs($attrVals);
        return !(count($state->getErrors()) > $initErrCount);
    }

    /**
     * Ensures that end marker "-\n" at the current location.
     *
     * @param  State $state
     * @retrun bool
     */
    protected function parseEndMarker(State $state) : bool
    {
        $cursor = $state->getCursor();
        if (!Scan::matchAhead('/\G-'.Rfc2849x::SEP_X.'/D', $cursor, PREG_UNMATCHED_AS_NULL)) {
            $state->errorHere('syntax error: expected "-" followed by end of line');
            return false;
        }
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
