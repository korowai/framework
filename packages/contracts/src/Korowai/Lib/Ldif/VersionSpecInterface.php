<?php
/**
 * @file src/Korowai/Lib/Ldif/VersionSpecInterface.php
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
 * Interface for objects representing LDIF version-spec.
 *
 * According to [RFC2849](https://tools.ietf.org/html/rfc2849), an LDIF file
 * begins with version-spec, which specifies the version number of the LDIF
 * format. The RFC2849 requires the version number to be 1. The
 * VersionSpecInterface encapsulates the version number and provides
 * SnippetInterface to handle information about the location of the
 * version-spec substring in LDIF file.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface VersionSpecInterface extends SnippetInterface
{
    /**
     * Returns the version number.
     *
     * @return int
     */
    public function getVersion(): int;
}

// vim: syntax=php sw=4 ts=4 et: