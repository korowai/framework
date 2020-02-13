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

use Korowai\Lib\Ldif\Traits\ParsesAttrValSpec;
use Korowai\Lib\Ldif\Traits\ParsesDnSpec;
use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;
use Korowai\Lib\Ldif\Traits\ParsesValueSpec;

/**
 * LDIF parser.
 */
class Parser implements ParserInterface
{
    // Include traits in appropriate order. If trait A declares an abstract
    // method which is documented in B, then A should go first, and B later.
    // This shall help sami with generating documentation correctly.
    use ParsesAttrValSpec;
    use ParsesDnSpec;
    use ParsesVersionSpec;
    use ParsesValueSpec;

    /**
     * {@inheritdoc}
     */
    public function parse(ParserStateInterface $state) : bool
    {
        return $this->parseLdifFile($state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
