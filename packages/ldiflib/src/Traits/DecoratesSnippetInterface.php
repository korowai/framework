<?php
/**
 * @file src/Traits/DecoratesSnippetInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\SnippetInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait DecoratesSnippetInterface
{
    use ExposesSnippetInterface;

    /**
     * @var SnippetInterface
     */
    protected $snippet;

    /**
     * Sets instance of SnippetInterface to this wrapper.
     *
     * @return $this
     */
    public function setSnippet(SnippetInterface $snippet)
    {
        $this->snippet = $snippet;
        return $this;
    }

    /**
     * Returns the encapsulated instance of SnippetInterface.
     *
     * @return SnippetInterface|null
     */
    public function getSnippet() : ?SnippetInterface
    {
        return $this->snippet;
    }
}

// vim: syntax=php sw=4 ts=4 et:
