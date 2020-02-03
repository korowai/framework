<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/VersionSpecInterface.php
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
 * Interface for the VersionSpec record.
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface VersionSpecInterface extends RecordInterface
{
    /**
     * Returns the version number stored as semantic value.
     *
     * @return int
     */
    public function getVersion() : int;
}

// vim: syntax=php sw=4 ts=4 et:
