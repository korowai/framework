<?php
/**
 * @file src/Rules/NewRdnSpecRule.php
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
 * A rule object that implements *newrdn-spec* rule defined in [Rfc2849x](\.\./\.\./Rfc/Rfc2849x.html).
 *
 * - semantic value: string
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class NewRdnSpecRule extends AbstractNameSpecRule
{
    protected static $b64Capture = 'rdn_b64';
    protected static $safeCapture = 'rdn_safe';
    protected static $rfcRuleSet = Rfc2849x::class;
    protected static $rfcRuleId = 'NEWRDN_SPEC_X';
    protected static $validator = [Util::class, 'rdnCheck'];
}

// vim: syntax=php sw=4 ts=4 et:
