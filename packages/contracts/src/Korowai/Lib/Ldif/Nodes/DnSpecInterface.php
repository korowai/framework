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
 * Interface for objects representing LDIF *dn-spec*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface DnSpecInterface extends NodeInterface
{
    /**
     * Returns the DN string.
     *
     * @return string
     */
    public function getDn(): string;
}

// vim: syntax=php sw=4 ts=4 et:
