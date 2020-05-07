<?php
/**
 * @file src/Traits/HasParserRules.php
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
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\VersionSpecRule;
use Korowai\Lib\Ldif\Exception\InvalidRuleNameException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait HasParserRules
{
    /**
     * @var array
     */
    protected $parserRules;

    /**
     * Returns instance of RuleInterface of given type.
     *
     * @param  string $ruleClass
     * @param  bool $tryOnly
     */
    protected function getParserRule(string $ruleClass, bool $tryOnly = false) : RuleInterface
    {
        if (!isset($this->parserRules[$ruleClass][$tryOnly])) {
            if (!is_subclass_of($ruleClass, RuleInterface::class)) {
                $message = 'Argument 1 passed to '.__class__.'::getParserRule() must be '.
                           'a name of class implementing '.RuleInterface::class.', '.
                           '"'.$ruleClass.'" given';
                throw new InvalidRuleNameException($message);
            }
            $this->parserRules[$ruleClass][$tryOnly] = new $ruleClass($tryOnly);
        }
        return $this->parserRules[$ruleClass][$tryOnly];
    }

    /**
     * Returns instance of AttrValSpecRule.
     *
     * @param  bool $tryOnly
     * @return AttrValSpecRule
     */
    public function attrValSpecRule(bool $tryOnly = false) : AttrValSpecRule
    {
        return $this->getParserRule(AttrValSpecRule::class, $tryOnly);
    }

    /**
     * Returns instance of DnSpecRule.
     *
     * @param  bool $tryOnly
     * @return DnSpecRule
     */
    public function dnSpecRule(bool $tryOnly = false) : DnSpecRule
    {
        return $this->getParserRule(DnSpecRule::class, $tryOnly);
    }

    /**
     * Returns instance of SepRule.
     *
     * @param  bool $tryOnly
     * @return SepRule
     */
    public function sepRule(bool $tryOnly = false) : SepRule
    {
        return $this->getParserRule(SepRule::class, $tryOnly);
    }

    /**
     * Returns instance of VersionSpecRule.
     *
     * @param  bool $tryOnly
     * @return VersionSpecRule
     */
    public function versionSpecRule(bool $tryOnly = false) : VersionSpecRule
    {
        return $this->getParserRule(VersionSpecRule::class, $tryOnly);
    }
}

// vim: syntax=php sw=4 ts=4 et:
