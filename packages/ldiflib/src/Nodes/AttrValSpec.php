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

/**
 * Semantic value of the
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *attrval-spec* rule.
 */
final class AttrValSpec implements AttrValSpecInterface
{
    /**
     * @var string
     */
    private $attribute;

    /**
     * @var ValueSpecInterface
     */
    private $valueObject;

    /**
     * Initializes the object.
     *
     * @param  string $attribute
     *      Attribute description string consisting of attribute type and
     *      options.
     * @param  ValueSpecInterface
     *      An object encapsulating the value of the attribute.
     */
    public function __construct(string $attribute, ValueSpecInterface $valueObject)
    {
        $this->attribute = $attribute;
        $this->valueObject = $valueObject;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute() : string
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueObject() : ValueSpecInterface
    {
        return $this->valueObject;
    }
}

// vim: syntax=php sw=4 ts=4 et:
