<?php
/**
 * @file src/Korowai/Lib/Ldif/Nodes/HasAttrValSpecsInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Nodes;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface HasAttrValSpecsInterface
{
    /**
     * Returns array of [AttrValInterface](AttrValInterface.html) objects that
     * comprise the record.
     *
     * @return array an array of [AttrValInterface](AttrValInterface.html) instances.
     */
    public function getAttrValSpecs() : array;
}

// vim: syntax=php sw=4 ts=4 et:
