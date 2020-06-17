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
 * *control* rule.
 */
final class Control implements ControlInterface
{
    /**
     * @var string
     */
    private $oid;

    /**
     * @var bool
     */
    private $criticality;

    /**
     * @var ValueSpecInterface
     */
    private $valueSpec;

    /**
     * Initializes the object.
     *
     * @param  string $oid
     *      OID string; specifies control type.
     * @param  bool $criticality
     *      Control's criticality, as defined in RFC2849.
     * @param  ValueSpecInterface
     *      An object encapsulating the value of the attribute.
     */
    public function __construct(string $oid, bool $criticality = null, ValueSpecInterface $valueSpec = null)
    {
        $this->oid = $oid;
        $this->criticality = $criticality;
        $this->valueSpec = $valueSpec;
    }

    /**
     * {@inheritdoc}
     */
    public function getOid() : string
    {
        return $this->oid;
    }

    /**
     * {@inheritdoc}
     */
    public function getCriticality() : ?bool
    {
        return $this->criticality;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueSpec() : ?ValueSpecInterface
    {
        return $this->valueSpec;
    }
}

// vim: syntax=php sw=4 ts=4 et:
