<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\NodeInterface;

/**
 * Interface for semantic value of the
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *control* rule.
 */
interface ControlInterface extends NodeInterface
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
    public function getValueSpec() : ?ValueSpecInterface;
}

// vim: syntax=php sw=4 ts=4 et:
