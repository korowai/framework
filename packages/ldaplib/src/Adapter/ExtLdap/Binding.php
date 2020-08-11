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

use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Binding implements BindingInterface, LdapLinkWrapperInterface
{
    use LastLdapExceptionTrait;
    use EnsureLdapLinkTrait;
    use LdapLinkWrapperTrait;

    /** @var bool */
    private $bound = false;

    /**
     * Initializes the Binding object with LdapLink instance.
     *
     * @param LdapLinkInterface $link
     */
    public function __construct(LdapLinkInterface $link)
    {
        $this->ldapLink = $link;
    }

    /**
     * {@inheritdoc}
     */
    public function isBound() : bool
    {
        return $this->bound;
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $dn = null, string $password = null)
    {
        $args = @func_get_args();
        return $this->callImplMethod('bindImpl', ...$args);
    }

    /**
     * Get LDAP option's value (as per ldap_get_option())
     *
     * @param  int $option Option identifier (name)
     * @return mixed Option value
     * @throws LdapException
     */
    public function getOption(int $option)
    {
        return $this->callImplMethod('getOptionImpl', $option);
    }

    /**
     * Set value to LDAP option
     *
     * @param  int $option Option identifier (name)
     * @param  mixed $value New value
     * @throws LdapException
     */
    public function setOption(int $option, $value)
    {
        return $this->callImplMethod('setOptionImpl', $option, $value);
    }

    /**
     * Unbinds the link
     *
     * After unbind the connection is no longer valid (and useful)
     *
     * @throws LdapException
     */
    public function unbind()
    {
        return $this->callImplMethod('unbindImpl');
    }

    /**
     * @internal
     */
    private function callImplMethod($name, ...$args)
    {
        $this->ensureLdapLink($this->getLdapLink());
        return with(LdapLinkErrorHandler::fromLdapLinkWrapper($this))(function () use ($name, $args) {
            return call_user_func_array([$this, $name], $args);
        });
    }

    /**
     * @internal
     */
    private function bindImpl(string $dn = null, string $password = null)
    {
        $args = func_get_args();
        $result = $this->getLdapLink()->bind(...$args);
        if (!$result) {
            $this->bound = false;
            throw static::lastLdapException($this->getLdapLink());
        }
        $this->bound = true;
        return $result;
    }

    /**
     * @internal
     */
    private function getOptionImpl(int $option)
    {
        if (!$this->getLdapLink()->get_option($option, $retval)) {
            throw static::lastLdapException($this->getLdapLink());
        }
        return $retval;
    }

    /**
     * @internal
     */
    public function setOptionImpl(int $option, $value)
    {
        if (!$this->getLdapLink()->set_option($option, $value)) {
            throw static::lastLdapException($this->getLdapLink());
        }
    }

    /**
     * @internal
     */
    private function unbindImpl()
    {
        if (!$this->getLdapLink()->unbind()) {
            throw static::lastLdapException($this->getLdapLink());
        }
        $this->bound = false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
