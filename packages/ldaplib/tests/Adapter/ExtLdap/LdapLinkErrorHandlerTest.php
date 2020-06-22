<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasLdapLink;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LastLdapException;

//
//// tests with process isolation can't use native PHP closures (they're not serializable)
//use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapGetOptionClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', ...$args);
    }

    /**
     * Returns an array of constraints based on $args for ldap function call
     * expectation.
     *
     * @return array
     */
    private function makeArgsForLdapMock(array $args, LdapLink $ldap = null) : array
    {
        if ($ldap !== null) {
            return array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
        }
        return array_map([$this, 'identicalTo'], $args);
    }

    /**
     * Returns new instance of LdapLink.
     *
     * @param  mixed $resource
     *
     * @return LdapLink
     */
    private static function createLdapLink($resource = 'ldap link') : LdapLink
    {
        return new LdapLink($resource);
    }

    public function test__uses__HasLdapLink()
    {
        $this->assertUsesTrait(HasLdapLink::class, LdapLinkErrorHandler::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
