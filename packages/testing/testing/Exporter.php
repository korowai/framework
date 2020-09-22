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

use SebastianBergmann\Exporter\Exporter as BaseExporter;
use SebastianBergmann\RecursionContext\Context;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Exporter extends BaseExporter
{
    /**
     * {@inheritdoc}
     */
    public function recursiveExport(&$value, $indentation, $processed = null)
    {
        if ($value instanceof ObjectPropertiesInterface) {

            $whitespace = str_repeat(' ', (int) (4 * $indentation));

            if (!$processed) {
                $processed = new Context;
            }

            if ($hash = $processed->contains($value)) {
                return sprintf('ObjectProperties');
            }

            $hash   = $processed->add($value);
            $values = '';
            $array  = $this->toArray($value);

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    $values .= sprintf(
                        '%s    %s => %s' . "\n",
                        $whitespace,
                        $this->recursiveExport($k, $indentation),
                        $this->recursiveExport($v, $indentation + 1, $processed)
                    );
                }

                $values = "\n" . $values . $whitespace;
            }

            return sprintf('ObjectProperties (%s)', $values);
        }

        return parent::recursiveExport($value, $indentation, $processed);
    }

    /**
     * {@inheritdoc}
     */
    public function shortenedExport($value)
    {
        if ($value instanceof ObjectPropertiesInterface) {
            return sprintf(
                'ObjectProperties (%s)',
                count($this->toArray($value)) > 0 ? '...' : ''
            );
        }
        return parent::shortenedExport($value);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
