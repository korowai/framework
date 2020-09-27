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

use SebastianBergmann\Exporter\Exporter as SebastianBergmannExporter;
use SebastianBergmann\RecursionContext\Context;

/**
 * An exporter that handles PropertiesInterface in a special way.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Exporter extends SebastianBergmannExporter
{
    public function describe(PropertiesInterface $properties): string
    {
        $header = 'Properties';
        if ($properties instanceof ExpectedPropertiesInterface) {
            $header .= ' <Expect>';
        } elseif ($properties instanceof ActualPropertiesInterface) {
            $header .= ' <Actual>';
        }

        return $header;
    }

    /**
     * Recursive implementation of export.
     *
     * @param mixed   $value       The value to export
     * @param int     $indentation The indentation level of the 2nd+ line
     * @param Context $processed   Previously processed objects
     *
     * @return string
     */
    public function recursiveExport(&$value, $indentation, $processed = null)
    {
        if ($value instanceof PropertiesInterface) {
            $whitespace = str_repeat(' ', (int) (4 * $indentation));

            if (!$processed) {
                $processed = new Context();
            }

            $hash = $processed->contains($value);

            if ($hash) {
                return $this->describe($value);
            }

            $hash = $processed->add($value);
            $values = '';
            $array = $this->toArray($value);

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    $values .= sprintf(
                        '%s    %s => %s'."\n",
                        $whitespace,
                        $this->recursiveExport($k, $indentation),
                        $this->recursiveExport($v, $indentation + 1, $processed)
                    );
                }

                $values = "\n".$values.$whitespace;
            }

            return sprintf('%s (%s)', $this->describe($value), $values);
        }

        return parent::recursiveExport($value, $indentation, $processed);
    }

    /**
     * Exports a value into a single-line string.
     *
     * The output of this method is similar to the output of
     * SebastianBergmann\Exporter\Exporter::export().
     *
     * Newlines are replaced by the visible string '\n'.
     * Contents of arrays and objects (if any) are replaced by '...'.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function shortenedExport($value)
    {
        if ($value instanceof PropertiesInterface) {
            return sprintf(
                '%s (%s)',
                $this->describe($value),
                count($this->toArray($value)) > 0 ? '...' : ''
            );
        }

        return parent::shortenedExport($value);
    }
}

// vim: syntax=php sw=4 ts=4 et:
