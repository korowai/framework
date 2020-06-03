<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\ParserStateInterface as State;

/**
 * @todo Write documentation
 *
 * - semantic value: string
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractRdnSpecRule extends AbstractNameSpecRule
{
    /**
     * {@inheritdoc}
     */
    public function prefix() : string
    {
        return 'rdn';
    }
}

// vim: syntax=php sw=4 ts=4 et:
