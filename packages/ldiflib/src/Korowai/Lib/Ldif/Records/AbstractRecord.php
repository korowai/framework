<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/AbstractRecord.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\CoupledRangeInterface;
use Korowai\Lib\Ldif\Traits\DecoratesCoupledRangeInterface;

/**
 * An abstract base class for parsed LDIF records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractRecord
{
    use DecoratesCoupledRangeInterface;

    /**
     * Initializes the object.
     *
     * @return object $this
     */
    public function initAbstractRecord(CoupledRangeInterface $range)
    {
        $this->setRange($range);
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
