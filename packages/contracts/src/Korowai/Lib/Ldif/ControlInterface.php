<?php
/**
 * @file src/Korowai/Lib/Ldif/ControlInterface.php
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
 * Interface for semantic value of the
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *control* rule.
 */
interface ControlInterface
{
    /**
     * Returns [RFC2849](https://tools.ietf.org/html/rfc2849#page-3)
     * control type OID as string.
     *
     * @return string
     */
    public function getOid() : string;

    /**
     * Returns the control's criticality as bool, or nul (if not specified).
     *
     * @return bool|null
     */
    public function getCriticality() : ?bool;

    /**
     * Returns an object representing control's value, or null (if not set).
     *
     * @return ValueInterface|null
     */
    public function getValueObject() : ?ValueInterface;
}

// vim: syntax=php sw=4 ts=4 et:
