<?php
/**
 * @file src/Parser.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * LDIF parser.
 */
class Parser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(ParserStateInterface $state) : bool
    {
        return $this->parseLdifFile($state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
