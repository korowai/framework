<?php
/**
 * @file src/Rules/NewSuperiorSpecRule.php
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
 * A rule object that implements *newsuperior-spec* rule defined in [Rfc2849x](\.\./\.\./Rfc/Rfc2849x.html).
 *
 * - semantic value: string
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class NewSuperiorSpecRule extends AbstractNameSpecRule
{
    protected static $b64Capture = 'dn_b64';
    protected static $safeCapture = 'dn_safe';
    protected static $rfcRuleSet = Rfc2849x::class;
    protected static $rfcRuleId = 'NEWSUPERIOR_SPEC_X';
    protected static $validator = [Util::class, 'dnCheck'];
}

// vim: syntax=php sw=4 ts=4 et:
