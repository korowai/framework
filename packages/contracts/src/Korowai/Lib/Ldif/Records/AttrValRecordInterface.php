<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/AttrValRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\RecordInterface;

/**
 * Interface for the AttrValRecord record.
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface AttrValRecordInterface extends RecordInterface
{
    /**
     * Returns the distinguished name stored as semantic value.
     *
     * @return string
     */
    public function getDn() : string;

    /**
     * @todo Write documentation
     */
    public function getAttrValSpecs() : array;
}

// vim: syntax=php sw=4 ts=4 et:
