<?php
/**
 * @file src/Traits/DecoratesRuleInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Rfc\Traits;

use Korowai\Lib\Rfc\RuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait DecoratesRuleInterface
{
    use ExposesRuleInterface;

    /**
     * @var RuleInterface|null
     */
    protected $rfcRule;

    /**
     * Returns the instance of RFC [RuleInterface](\.\./RuleInterface.html)
     * encapsulated by this object.
     *
     * @return RuleInterface
     */
    public function getRfcRule() : ?RuleInterface
    {
        return $this->rfcRule;
    }

    /**
     * Sets the new instance of RFC [RuleInterface](\.\./RuleInterface.html) to
     * this object.
     *
     * @param  RuleInterface $rfcRule
     *
     * @return object $this
     */
    public function setRfcRule(?RuleInterface $rfcRule)
    {
        $this->rfcRule = $rfcRule;
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
