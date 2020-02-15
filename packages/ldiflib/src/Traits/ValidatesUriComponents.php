<?php
/**
 * @file src/Traits/ValidatesUriComponents.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

/**
 * Methods for validation of the Uri initializer. Only simple checks for
 * existence of certain components are performed.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ValidatesUriComponents
{
    protected function validateComponents(array $components)
    {
        if (($components['uri'] ?? null) !== null) {
            $this->validateUri($components);
        } elseif (($components['relative_ref'] ?? null) !== null) {
            $this->validateRelativeRef($components);
        } else {
            throw new \InvalidArgumentException('none of "uri" nor "relative_ref" components provided');
        }
    }

    protected function validateUri(array $components)
    {
        if (empty($components['scheme'] ?? null)) {
            throw new \InvalidArgumentException('missing or empty "scheme" component');
        }
        $this->validateHierPart($components);
    }

    protected function validateRelativeRef(array $components)
    {
        $this->validateRelativePart($components);
    }

    protected function validateHierPart(array $components)
    {
        if (($components['hier_part'] ?? null) === null) {
            throw new \InvalidArgumentException('missing "hier_part" component');
        }
        $paths = [
            'path_absolute' => true,
            'path_rootless' => true,
            'path_empty' => true
        ];
        $this->validatePart($components, $paths);
    }

    protected function validateRelativePart(array $components)
    {
        if (($components['relative_part'] ?? null) === null) {
            throw new \InvalidArgumentException('missing "relative_part" component');
        }
        $paths = [
            'path_absolute' => true,
            'path_noscheme' => true,
            'path_empty' => true
        ];
        $this->validatePart($components, $paths);
    }

    protected function validatePart(array $components, array $paths)
    {
        if (($components['authority'] ?? null) !== null) {
            $this->validateAuthority($components);
            if (($components['path_abempty'] ?? null) === null) {
                throw new \InvalidArgumentException('missing "path_abempty" component');
            }
        } else {
            $pathComponents = array_intersect_key($components, $paths);
            if (empty($pathComponents)) {
                $what = array_map(function (string $s) {
                    return '"'.$s.'"';
                }, array_keys($paths));
                $what = implode(', ', array_slice($what, 0, -1)).' nor "'.end($what).'"';
                $message = 'none of '.$what.' components provided';
                throw new \InvalidArgumentException($message);
            }
        }
    }

    protected function validateAuthority(array $components)
    {
        $this->validateHost($components);
    }

    protected function validateHost(array $components)
    {
        if (($components['host'] ?? null) === null) {
            throw new \InvalidArgumentException('missing "host" component');
        }
        if (($components['ip_literal'] ?? null) !== null) {
            $this->validateIpLiteral($components);
        } elseif (empty($components['ipv4address'] ?? null) && ($components['reg_name'] ?? null) === null) {
            throw new \InvalidArgumentException('none of "ip_literal", "ipv4address" nor "reg_name" provided');
        }
    }

    protected function validateIpLiteral(array $components)
    {
        if (($components['ipv6address'] ?? null) === null && ($components['ipvfuture'] ?? null) === null) {
            throw new \InvalidArgumentException('none of "ipv6address" nor "ipvfuture" components provided');
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
