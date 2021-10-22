<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Behat;

use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\LdapInterface;
use Psr\Container\ContainerInterface;

trait LdapHelperTrait
{
    abstract public function getContainer(): ContainerInterface;

    abstract public function getLdap(): ?LdapInterface;
    abstract protected function setLdap(?LdapInterface $ldap): void;

    abstract protected function clearResults(): void;
    abstract protected function appendResult($result): void;
    abstract protected function lastResult();

    abstract protected function clearExceptions(): void;
    abstract protected function appendException($exception): void;
    abstract protected function lastException();

    protected function initLdapHelper()
    {
        $this->clearExceptions();
        $this->clearResults();
    }

    protected function createLdapLinkWithConfig($config)
    {
        try {
            $ldapFactory = $this->getContainer()->get(LdapFactoryInterface::class);
            $ldap = $ldapFactory->createLdapInterface($config);
            $this->setLdap($ldap);
        } catch (\Exception $exception) {
            $this->appendException($exception);
        }
    }

    protected function bindWithArgs(...$args)
    {
        try {
            return $this->getLdap()->bind(...$args);
        } catch (\Exception $exception) {
            $this->appendException($exception);

            return false;
        }
    }

    protected function searchWithArgs(...$args)
    {
        try {
            $result = $this->getLdap()->search(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);

            return false;
        }
        $this->appendResult($result);

        return $result;
    }

    protected function compareWithArgs(...$args)
    {
        try {
            $result = $this->getLdap()->compare(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);

            return false;
        }
        $this->appendResult($result);

        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
