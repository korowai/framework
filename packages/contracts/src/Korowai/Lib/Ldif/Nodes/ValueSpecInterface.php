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
 * Interface for semantic value of RFC2849 *value-spec* rule.
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ValueSpecInterface extends NodeInterface
{
    /**
     * Type enum for the RFC2849 SAFE-STRING value.
     */
    public const TYPE_SAFE = 0;

    /**
     * Type enum for the RFC2849 BASE64-STRING value.
     */
    public const TYPE_BASE64 = 1;

    /**
     * Type enum for the RFC2849 URL value.
     */
    public const TYPE_URL = 2;

    /**
     * Returns an integer which specifies the type of this value.
     *
     * @return int
     *      Returns one of ``TYPE_SAFE`` (0), ``TYPE_BASE64`` (1) or ``TYPE_URL`` (2).
     */
    public function getType() : int;

    /**
     * Returns a value or an object which specifies the content.
     *
     * The value's content and specification are not equivalent terms.
     * File contents (content), for example, are specified by its URL
     * (specification). Similarly, for a base64-encoded string, the
     * original string shall be understood as a specification and the decoded
     * string is the content.
     *
     * @return mixed
     *      Returns a value or object that specifies the content.
     */
    public function getSpec();

    /**
     * Returns the decoded contents of the value.
     *
     * RFC2849 defines a value as one of SAFE-STRING, BASE64-STRING or URL.
     * This method returns the actual content, either SAFE-STRING, decoded
     * BASE64-STRING or the content of the file pointed to by the URL.
     *
     * @return string
     */
    public function getContent() : string;
}

// vim: syntax=php sw=4 ts=4 et:
