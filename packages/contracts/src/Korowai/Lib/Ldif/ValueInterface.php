<?php
/**
 * @file src/Korowai/Lib/Ldif/ValueInterface.php
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
 * Interface for semantic value of RFC2849 value-spec rule.
 */
interface ValueInterface
{
    /**
     * Returns the decoded contents of the value.
     *
     * RFC2849 defines a value as one of SAFE-STRING, BASE64-STRING or URL.
     * This method returns the actual content, the SAFE-STRING, decoded
     * BASE64-STRING or the content of the file pointed to by the URL.
     *
     * @return string
     */
    public function getContent() : string;
}

// vim: syntax=php sw=4 ts=4 et:
