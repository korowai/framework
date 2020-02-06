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
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\ParsesDnSpec;
use Korowai\Lib\Ldif\Traits\ParsesStrings;
use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;

/**
 * LDIF parser.
 */
class Parser implements ParserInterface
{
    use MatchesPatterns;
    use ParsesAttrValSpec;
    use ParsesDnSpec;
    use ParsesStrings;
    use ParsesVersionSpec;

    /**
     * {@inheritdoc}
     */
    public function parse(ParserStateInterface $state) : bool
    {
        return $this->parseLdifFile($state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
