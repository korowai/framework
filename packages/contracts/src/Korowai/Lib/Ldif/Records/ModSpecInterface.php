<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/ModSpecInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\SnippetInterface;

/**
 * Interface for objects representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849) *mod-spec*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ModSpecInterface extends SnippetInterface, AttrValSpecsInterface
{
    /**
     * Returns the modification type. Must return one of ``"add"``,
     * ``"delete"`` or ``"replace"``.
     *
     * @return string
     */
    public function getModType() : string;

    /**
     * Returns the attribute name.
     *
     * @return string
     */
    public function getAttribute() : string;
}

// vim: syntax=php sw=4 ts=4 et:
