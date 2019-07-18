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
 * Represents value-spec (RFC2849)
 */
class ValueSpec extends Terminal
{
    const SAFE_STRING = Value::SAFE_STRING;
    const BASE64_STRING = Value::BASE64_STRING;
    const URL = Value::URL;

    protected $value;

    public function __construct(Value $value, string $code=null,
                                int $start=null, int $end=null)
    {
        $this->init($value, $code, $start, $end);
    }

    public function init(Value $value, string $code=null,
                         string $start=null, string $end=null)
    {
        $this->initTerminal($code ?? ':'.$value->getCode(), $start, $end);
        $this->value = $value;
        return $this;
    }

    /**
     * Returns the stored DN.
     *
     * @return string
     */
    public function getValue()
    {
        return (string)($this->value);
    }

    public function getKind()
    {
        return $this->value->getKind();
    }

    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'value-spec';
    }
}

// vim: syntax=php sw=4 ts=4 et:
