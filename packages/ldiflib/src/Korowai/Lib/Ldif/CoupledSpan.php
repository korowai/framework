<?php
/**
 * @file src/Korowai/Lib/Ldif/CoupledSpan.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Traits\WrapsCoupledLocation;

/**
 * Points at a character of preprocessed input.
 */
class CoupledSpan implements CoupledSpanInterface
{
    use WrapsCoupledLocation;

    /**
     * @var int
     */
    protected $endLocation;

    /**
     * Initializes the object.
     *
     * @param  CoupledLocationInterface $begin Location of the beginning of the span.
     * @param  CoupledLocationInterface $end Location of the end of the span.
     */
    public function __construct(CoupledLocationInterface $begin, CoupledLocationInterface $end)
    {
        $this->init($location, $length);
    }

    /**
     * Initializes the object.
     *
     * @param  CoupledLocationInterface $input Preprocessed source code.
     * @param  int $position Character offset (in bytes) the $input,
     */
    public function init(CoupledLocationInterface $location, int $length)
    {
        $this->setLocation($location);
        $this->setByteLength($length);
    }

    /**
     * Sets the length of span.
     *
     * @param  int $length
     * @return CoupledSpanInterface $this
     */
    public function setByteLength(int $length) : CoupledSpanInterface
    {
        $this->length = $length;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteLength() : int
    {
        $im = $this->getLocation()->
        return $this->getLocation()->
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharLength(int $encoding = null) : int
    {
        $args = func_get_args();
        return $this->findCharLength(
            $this->getSourceString(),
            $this->getSourceByteOffset(),
            $this->getSourceByteLength(),
            ...$args
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteEndOffset() : int
    {
        $this->getByteEndOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharEndOffset(string $encoding = null) : int
    {
        $args = func_get_args();
        return $this->getSourceCharOffset(...$args) + $this->getSourceCharLength(...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function getByteLength() : int
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function getCharLength(int $encoding = null) : int
    {
        $args = func_get_args();
        return $this->findCharLength(
            $this->getString(),
            $this->getByteOffset(),
            $this->getByteLength(),
            ...$args
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getByteEndOffset() : int
    {
        return $this->getByteOffset() + $this->getByteLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getCharEndOffset(string $encoding = null) : int
    {
        $args = func_get_args();
        return $this->getCharOffset(...$args) + $this->getCharLength(...$args);
    }

    /**
     * Helps find the srint length in terms of characters.
     *
     * @param  string $str
     * @param  int $offset
     * @param  int $length
     *
     * @return int
     */
    protected function findCharLength(string $str, int $offset, int $length, string $encoding = null) : int
    {
        $tail = array_slice(func_get_args(), 3);
        return mb_strlen(substr($str, $offset, $length), ...$tail);
    }
}

// vim: syntax=php sw=4 ts=4 et:
