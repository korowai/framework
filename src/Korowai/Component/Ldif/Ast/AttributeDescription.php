<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Ast;

/**
 * Represents AttributeDescription (RFC2849)
 */
class AttributeDescription extends Terminal
{
    protected $attrDesc;

    /**
     * Initializes the object.
     *
     * @param array $attrDesc an array with one or more elements; the first
     *                        element provides AttributeType, the remaining
     *                        elements provide attribute options (see RFC2849).
     * @param string $code
     * @param int $start
     * @param int $end
     */
    public function __construct(array $attrDesc, string $code=null, int $start=null, int $end=null)
    {
        $this->init($attrDesc, $code, $start, $end);
    }

    /**
     * Initializes the object.
     *
     * @param array $attrDesc an array with one or more elements; the first
     *                        element provides AttributeType, the remaining
     *                        elements provide attribute options (see RFC2849).
     * @param string $code
     * @param int $start
     * @param int $end
     */
    public function init(array $attrDesc, string $code=null, string $start=null, string $end=null)
    {
        $this->initTerminal($code ?? implode(';', $attrDesc), $start, $end);
        $this->attrDesc = $attrDesc;
        return $this;
    }

    /**
     * Returns the stored attribute description.
     *
     * The returned value is an array with at least one entry.
     *
     * @return array
     */
    public function getValue()
    {
        return $this->attrDesc;
    }

    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'AttributeDescription';
    }
}

// vim: syntax=php sw=4 ts=4 et:
