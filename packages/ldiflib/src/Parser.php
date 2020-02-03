<?php
/**
 * @file src/Korowai/Lib/Ldif/Parser.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\ParsesDnSpec;
use Korowai\Lib\Ldif\Traits\ParsesLdifFile;
use Korowai\Lib\Ldif\Traits\ParsesStrings;
use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;

/**
 * LDIF parser.
 */
class Parser implements ParserInterface
{
    use MatchesPatterns;
//    use ParsesDnSpec;
//    use ParsesLdifFile;
//    use ParsesStrings;
    use ParsesVersionSpec;
    use SkipsWhitespaces;

    /**
     * {@inheritdoc}
     */
    public function parse(ParserStateInterface $state) : bool
    {
        return $this->parseLdifFile($state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
