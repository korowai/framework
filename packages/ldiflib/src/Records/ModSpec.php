<?php
/**
 * @file src/Records/ModSpec.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Lib\Ldif\Exception\InvalidModTypeException;

/**
 * Represents [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *mod-spec*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModSpec implements ModSpecInterface
{
    use DecoratesSnippetInterface;
    use HasAttrValSpecs;

    /**
     * @var string
     */
    private $modType;

    /**
     * @var string
     */
    private $attribute;

    /**
     * Initializes the object.
     *
     * @param  SnippetInterface $snippet
     * @param  string $modType
     * @param  string $attribute
     * @param  array $attrValSpecs
     *
     * @throws InvalidModTypeException
     */
    public function __construct(
        SnippetInterface $snippet,
        string $modType,
        string $attribute,
        array $attrValSpecs = []
    ) {
        $this->setSnippet($snippet);
        $this->setModType($modType);
        $this->setAttribute($attribute);
        $this->setAttrValSpecs($attrValSpecs);
    }

    /**
     * Sets modType. Allowed values of *$modType* are ``"add"``, ``"delete"``,
     * and ``"replace"``.
     *
     * @param  string $modType
     * @return object $this
     * @throws InvalidModTypeException
     */
    public function setModType(string $modType)
    {
        if (!in_array(strtolower($modType), ['add', 'delete', 'replace'])) {
            $message = 'Argument 1 to '.__class__.'::setModType() must be one of "add", "delete", or "replace", "'.
                       $changeType.'" given.';
            throw new InvalidModTypeException($message);
        }
        $this->modType = strtolower($modType);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModType() : string
    {
        return $this->modType;
    }

    /**
     * Set the attribute name.
     *
     * @param  string $attribute
     * @return object $this
     */
    public function setAttribute(string $attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute() : string
    {
        return $this->attribute;
    }
}

// vim: syntax=php sw=4 ts=4 et:
