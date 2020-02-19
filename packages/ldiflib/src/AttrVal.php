<?php
/**
 * @file src/AttrVal.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Semantic value of RFC2849 ``attrval-spec`` rule.
 */
class AttrVal implements AttrValInterface
{
    /**
     * @var string
     */
    private $attributeDescription;

    /**
     * @var ValueInterface
     */
    private $value;

    /**
     * Initializes the object.
     */
    public function __construct(string $attributeDescription, ValueInterface $value)
    {
        $this->attributeDescription = $attributeDescription;
        $this->value = $value;
    }

    /**
     * Returns attribute-description string consisting of attribute type and
     * options.
     *
     * @return string
     */
    public function getAttributeDescription() : string
    {
        return $this->attributeDescription;
    }

    /**
     * Returns the object representing attribute value.
     *
     * @return ValueInterface
     */
    public function getValue() : ValueInterface
    {
        return $this->value;
    }
}

// vim: syntax=php sw=4 ts=4 et:
