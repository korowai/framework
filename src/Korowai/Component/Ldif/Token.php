<?php
/**
 * @file src/Korowai/Component/Ldif/Token.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

/**
 * An abstract base class for a piece of LDIF file.
 */
class Token
{
    /**
     * @var int
     */
    protected $position;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $source;


    /**
     * Initializes the Token object.
     *
     * @param int $position Offset (in bytes) where the token starts in $source.
     * @param int $length Length of the token (in bytes).
     * @param int $source ???
     */
    public function __construct(int $position, int $length, string $source)
    {
        $this->init($position, $length, $source);
    }

    /**
     * Initializes the Token object.
     *
     * @param int $position Offset (in bytes) where the token starts in a string.
     * @param int $length Length of the token (in bytes).
     * @param int $source ???.
     */
    public function init(int $position, int $length, string $source)
    {
        $this->position = $position;
        $this->length = $length;
        $this->source = $source;
    }

    /**
     * Return the source string where the token originated from.
     */
    public function getSource()
    {
        return $this->source;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getContent()
    {
        return substr($this->source, $this->position, $this->length);
    }

    public function __toString()
    {
        return $this->getContent();
    }
}

// vim: syntax=php sw=4 ts=4 et:
