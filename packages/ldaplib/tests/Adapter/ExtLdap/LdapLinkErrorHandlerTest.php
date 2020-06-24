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
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LastLdapException;
use Korowai\Lib\Error\AbstractManagedErrorHandler;

//
//// tests with process isolation can't use native PHP closures (they're not serializable)
//use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapGetOptionClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

//    private function getLdapFunctionMock(...$args)
//    {
//        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', ...$args);
//    }
//
//    /**
//     * Returns an array of constraints based on $args for ldap function call
//     * expectation.
//     *
//     * @return array
//     */
//    private function makeArgsForLdapMock(array $args, LdapLink $ldap = null) : array
//    {
//        if ($ldap !== null) {
//            return array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
//        }
//        return array_map([$this, 'identicalTo'], $args);
//    }
//
//    /**
//     * Returns new instance of LdapLink.
//     *
//     * @param  mixed $resource
//     *
//     * @return LdapLink
//     */
//    private static function createLdapLink($resource = 'ldap link') : LdapLink
//    {
//        return new LdapLink($resource);
//    }

    private function createLdapLinkMock($resource = 'ldap link', array $methods = []) : LdapLinkInterface
    {
        $builder = $this->getMockBuilder(LdapLinkInterface::class);
        if ($resource !== null && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        $builder->setMethods($methods);

        $mock = $builder->getMockForAbstractClass();

        if ($resource !== null) {
            $mock->expects($this->any())
                 ->method('getResource')
                 ->with()
                 ->willReturn($resource);
        }

        return $mock;
    }

    public function test__extends__AbstractManagedErrorHandler()
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, LdapLinkErrorHandler::class);
    }

    public function test__uses__HasLdapLink()
    {
        $this->assertUsesTrait(HasLdapLink::class, LdapLinkErrorHandler::class);
    }

    public function test__getLdapLink()
    {
        $ldap = $this->createLdapLinkMock();
        $handler = new LdapLinkErrorHandler($ldap);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    public static function prov__invoke()
    {
        return [
            // #0
            [
                'ldapMethods' => [
                    'errno' => false,
                ]
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__invoke
     */
    public function test__invoke(array $ldapMethods)
    {
        $ldap = $this->createLdapLinkMock('ldap link', $ldapMethods);

        $ldap->expects(
    }
}

// vim: syntax=php sw=4 ts=4 et:
