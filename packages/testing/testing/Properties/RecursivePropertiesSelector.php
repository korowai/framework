<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Properties;

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

    /**
     * @param mixed $subject
     */
    public function selectProperties($subject): ActualPropertiesInterface
    {
        return new ActualProperties($this->selectPropertiesArray($subject));
    }

    /**
     * @param mixed $subject
     */
    private function selectPropertiesArray($subject): array
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

    /**
     * @param mixed $actual
     * @param mixed $expect
     *
     * @return mixed
     */
    private function adjustActualValue($actual, $expect)
    {
        if ($expect instanceof ExpectedPropertiesInterface) {
            if ($expect->getPropertySelector()->canSelectFrom($actual)) {
                return (new RecursivePropertiesSelector($expect))->selectProperties($actual);
            }
        } elseif (is_array($expect) && is_array($actual)) {
            foreach ($actual as $key => &$val) {
                $val = self::adjustActualValue($val, $expect[$key]);
            }
        }

        return $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
