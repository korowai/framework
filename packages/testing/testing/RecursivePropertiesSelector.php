<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class RecursivePropertiesSelector implements RecursivePropertiesSelectorInterface
{
    /**
     * @var ExpectedPropertiesInterface
     */
    private $expected;

    public function __construct(ExpectedPropertiesInterface $expected)
    {
        $this->expected = $expected;
    }

    public function selectProperties($subject) : ActualPropertiesInterface
    {
        return new ActualProperties($this->selectPropertiesArray($subject));
    }

    private function selectPropertiesArray($subject) : array
    {
        $array = [];
        $selector = $this->expected->getPropertySelector();
        // order of keys in $array shall follow the given sequence in $this->expected
        foreach ($this->expected as $key => $expect) {
            if ($selector->selectProperty($subject, $key, $actual)) {
                $array[$key] = $this->adjustActualValue($actual, $expect);
            }
        }
        return $array;
    }

    private function adjustActualValue($value, $expect)
    {
        if ($expect instanceof ExpectedPropertiesInterface) {
            if ($expect->canSelectFrom($value)) {
                return (new RecursivePropertiesSelector($expect))->selectProperties($value);
            }
        } elseif (is_array($expect) && is_array($value)) {
            foreach ($value as $key => &$val) {
                $val = self::adjustActualValue($val, $expect[$key]);
            }
        }
        return $value;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
