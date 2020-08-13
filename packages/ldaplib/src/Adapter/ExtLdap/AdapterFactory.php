<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\AbstractAdapterFactory;
use Korowai\Lib\Ldap\Exception\LdapException;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\exceptionErrorHandler;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class AdapterFactory extends AbstractAdapterFactory
{
    use LdapLinkOptionsTrait;
    use EnsureLdapLinkTrait;
    use LastLdapExceptionTrait;

    /**
     * Creates instance of AdapterFactory
     *
     * @throws LdapException
     */
    public function __construct(array $config = [])
    {
        if (!@extension_loaded('ldap')) {
            throw new LdapException("The LDAP PHP extension is not enabled.", -1);
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureNestedOptionsResolver(OptionsResolver $resolver) : void
    {
        $this->configureLdapLinkOptions($resolver);
    }

    private function createLdapLink() : LdapLinkInterface
    {
        $handler = exceptionErrorHandler(
            /** @psalm-param mixed ...$args */
            function (int $severity, string $message, ...$args) {
                return new LdapException($message, -1, $severity, ...$args);
            }
        );
        $link = with($handler)(
            /** @psalm-return LdapLinkInterface|false */
            function () {
                $config = $this->getConfig();
                return LdapLink::connect($config['uri']);
            }
        );
        if (!$link) {
            // throw this exception in case ldap-ext forgot to trigger_error
            throw new LdapException('Failed to create LDAP connection', -1);
        }
        return $link;
    }

    private function configureLdapLink(LdapLinkInterface $link) : void
    {
        $config = $this->getConfig();
        foreach ($config['options'] as $name => $value) {
            $option = $this->getLdapLinkOptionConstant($name);
            $this->setLdapLinkOption($link, $option, $value);
        }
    }

    /**
     * @param LdapLinkInterface $link
     * @param int $option
     * @param mixed $value
     */
    private function setLdapLinkOption(LdapLinkInterface $link, int $option, $value) : void
    {
        static::ensureLdapLink($link);
        with(new LdapLinkErrorHandler($link))(function () use ($link, $option, $value) {
            $this->setLdapLinkOptionImpl($link, $option, $value);
        });
    }

    /**
     * @param LdapLinkInterface $link
     * @param int $option
     * @param mixed $value
     */
    private function setLdapLinkOptionImpl(LdapLinkInterface $link, int $option, $value) : void
    {
        if (!$link->set_option($option, $value)) {
            throw static::lastLdapException($link);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createAdapter() : AdapterInterface
    {
        $link = $this->createLdapLink();
        $this->configureLdapLink($link);
        return new Adapter($link);
    }
}

// vim: syntax=php sw=4 ts=4 et:
