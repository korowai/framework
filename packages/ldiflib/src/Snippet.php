<?php
/**
 * @file src/Snippet.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Traits\DecoratesLocationInterface;

/**
 * @todo Write documentation.
 */
class Snippet implements SnippetInterface
{
    use DecoratesLocationInterface;

    /**
     * @var int
     */
    protected $length;

    /**
     * Initializes the object.
     *
     * @param  LocationInterface $location
     * @param  int $length
     */
    public function __construct(LocationInterface $location, int $length)
    {
        $this->init($location, $length);
    }

    /**
     * Initializes the object.
     *
     * @param  LocationInterface $location
     * @param  int $length
     *
     * @return Snippet $this
     */
    public function init(LocationInterface $location, int $length)
    {
        $this->setLocation($location);
        $this->setLength($length);
        return $this;
    }

    /**
     * Sets the length of span.
     *
     * @param  int $length
     * @return Snippet $this
     */
    public function setLength(int $length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLength() : int
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndOffset() : int
    {
        return $this->getOffset() + $this->getLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLength() : int
    {
        return $this->getSourceEndOffset() - $this->getSourceOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceEndOffset() : int
    {
        return $this->getInput()->getSourceOffset($this->getEndOffset());
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
        return $this->getInput()->getSourceCharOffset($this->getEndOffset(), ...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
