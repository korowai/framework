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
 * *attrval-spec* rule.
 */
interface AttrValSpecInterface extends NodeInterface
{
    /**
     * Returns [RFC2849](https://tools.ietf.org/html/rfc2849#page-3)
     * AttributeDescription string consisting of attribute type and
     * options.
     *
     * @return string
     */
    public function getAttribute() : string;

    /**
     * Returns the object representing attribute value.
     *
     * @return ValueSpecInterface
     */
    public function getValueSpec() : ValueSpecInterface;
}

// vim: syntax=php sw=4 ts=4 et:
