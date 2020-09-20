<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib\Symfony;

use Symfony\Component\Config\Loader\LoaderInterface;
use function Korowai\Ldaplib\config_path;

/**
 * Abstract base class for integration testing of korowai/ldiflib with Symfony.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\Symfony\TestCase
{
    use \Korowai\Testing\Ldaplib\ExamineLdaplibContainerTrait;

    protected function registerContainerConfiguration(LoaderInterface $loader) : void
    {
        $loader->load(config_path('symfony/services.php'));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
