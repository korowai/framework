<?php
/**
 * @file src/Korowai/Lib/Ldif/CoupledRange.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Traits\DecoratesCoupledLocationInterface;

/**
 * @todo Write documentation.
 */
class CoupledRange implements CoupledRangeInterface
{
    use DecoratesCoupledLocationInterface;

    /**
     * @var int
     */
    protected $length;

    /**
     * Initializes the object.
     *
     * @param  CoupledLocationInterface $location
     * @param  int $length
     */
    public function __construct(CoupledLocationInterface $location, int $length)
    {
        $this->init($location, $length);
    }

    /**
     * Initializes the object.
     *
     * @param  CoupledLocationInterface $location
     * @param  int $length
     *
     * @return CoupledRange $this
     */
    public function init(CoupledLocationInterface $location, int $length)
    {
        $this->setLocation($location);
        $this->setByteLength($length);
        return $this;
    }

    /**
     * Sets the length of span.
     *
     * @param  int $length
     * @return CoupledRange $this
     */
    public function setByteLength(int $length)
    {
        $this->length = $length;
        return $this;
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
    public function getByteEndOffset() : int
    {
        return $this->getByteOffset() + $this->getByteLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteLength() : int
    {
        return $this->getSourceByteEndOffset() - $this->getSourceByteOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteEndOffset() : int
    {
        return $this->getInput()->getSourceByteOffset($this->getByteEndOffset());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharLength(string $encoding = null) : int
    {
        $args = func_get_args();
        return $this->getSourceCharEndOffset(...$args) - $this->getSourceCharOffset(...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharEndOffset(string $encoding = null) : int
    {
        $args = func_get_args();
        return $this->getInput()->getSourceCharOffset($this->getByteEndOffset(), ...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
