<?php
/**
 * @file packages/errorlib/AbstractManagedErrorHandler.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

use Korowai\Lib\Context\ContextManagerInterface;

/**
 * Abstract base class for context-managed error handlers.
 *
 * The base class implements enterContext() and exitContext(). The user has to
 * implement __invoke() method as defined in ErrorHandlerInterface.
 */
abstract class AbstractManagedErrorHandler extends AbstractErrorHandler implements ContextManagerInterface
{
    use ContextManagerMethods;
}

// vim: syntax=php sw=4 ts=4 et:
