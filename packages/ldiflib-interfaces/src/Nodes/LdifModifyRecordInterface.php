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
 * Interface for a record object representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *ldif-change-record* of type *change-modify*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdifModifyRecordInterface extends LdifChangeRecordInterface, NodeInterface
{
    /**
     * Returns array of [ModSpecInterface](ModSpecInterface.html) objects.
     *
     * @return array
     */
    public function getModSpecs() : array;
}

// vim: syntax=php sw=4 ts=4 et:
