<?php
/**
 * @file src/Korowai/Lib/Ldif/AttrValRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for record objects representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849) ldif-attrval-records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface AttrValRecordInterface extends RecordInterface
{
    /**
     * Returns array of [AttrValInterface](AttrValInterface.html) objects that
     * comprise the record.
     *
     * @return array
     */
    public function getAttrValSpecs() : array;
}

// vim: syntax=php sw=4 ts=4 et:
