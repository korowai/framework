<?php
/**
 * @file src/Rules/DnSpecRule.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Rules;

use Korowai\Lib\Rfc\Rfc2849x;

/**
 * A rule object that implements *dn-spec* rule defined in RFC2849.
 *
 * - semantic value: string
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class DnSpecRule extends AbstractNameSpecRule
{
    protected static $b64Capture = 'dn_b64';
    protected static $safeCapture = 'dn_safe';
    protected static $rfcRuleSet = Rfc2849x::class;
    protected static $rfcRuleId = 'DN_SPEC_X';
    protected static $validator = [Util::class, 'dnCheck'];
}

// vim: syntax=php sw=4 ts=4 et:
