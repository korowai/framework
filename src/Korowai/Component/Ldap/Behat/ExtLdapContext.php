<?php
/**
 * @file src/Korowai/Component/Ldap/Behat/ExtLdapContext.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldap
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldap\Behat;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AftereScenarioScope;

use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Exception\LdapException;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class ExtLdapContext implements Context
{
    private $ldap;
    private $exceptions;

    private static $ldapServiceConfig = [
        'host' => 'ldap-service',
        'username' => 'cn=admin,dc=example,dc=org',
        'password' => 'admin',
        'bindRequiresDn' => true,
        'accountDomainName' => 'example.org',
        'baseDn' => 'dc=example,dc=org'
    ];
    private static $ldapServiceManager = null;

    public static function getLdapServiceConfig()
    {
        return self::$ldapServiceConfig;
    }

    public static function getLdapServiceManager()
    {
        if (is_null(self::$ldapServiceManager)) {
            $ldap = new \Zend\Ldap\Ldap(self::getLdapServiceConfig());
            @ldap_set_option($ldap->getResource(), LDAP_OPT_SERVER_CONTROLS, [['oid' => LDAP_CONTROL_MANAGEDSAIT]]);
            $ldap->bind();
            self::$ldapServiceManager = $ldap;
        }
        return self::$ldapServiceManager;
    }

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->resetExceptions();
        $this->resetQueryResults();
    }

    /**
     * @BeforeScenario @initDbBeforeScenario
     * @BeforeSuite @initDbBeforeSuite
     * @BeforeFeature @initDbBeforeFeature
     * @AfterScenario @initDbAfterScenario
     * @AfterSuite @initDbAfterSuite
     * @AfterFeature @initDbAfterFeature
     */
    public static function initDb()
    {
        $ldap = self::getLdapServiceManager();
        self::deleteLdapDescendants($ldap, 'dc=example,dc=org', '(&(objectclass=*)(!(cn=admin)))');
        return self::addFromLdifFile($ldap, __DIR__.'/../Resources/ldif/bootstrap.ldif');
    }

    protected static function deleteLdapDescendants($ldap, $base, $filter=null)
    {
        $deleted = [];
        $result = $ldap->search($filter ?? '(objectclass=*)', $base, \Zend\Ldap\Ldap::SEARCH_SCOPE_ONE, ['dn']);
        if($result) {
            foreach ($result as $entry) {
                $ldap->delete($entry['dn'], true);
                $deleted[] = $entry['dn'];
            }
        }
        return $deleted;
    }

    protected static function addFromLdifFile($ldap, $file)
    {
        $ldif = file_get_contents($file);
        return self::addFromLdifString($ldap, $ldif);
    }

    protected static function addFromLdifString($ldap, $ldif)
    {
        $entries = \Zend\Ldap\Ldif\Encoder::decode($ldif);
        foreach ($entries as $entry) {
            $ldap->add($entry['dn'], $entry);
        }
    }

    protected function resetExceptions()
    {
        $this->exceptions = array();
    }

    protected function resetQueryResults()
    {
        $this->results = array();
    }

    protected function appendException($e)
    {
        $this->exceptions[] = $e;
    }

    protected function appendResult($result)
    {
        $this->results[] = $result;
    }

    protected function lastException()
    {
        if (count($this->exceptions) < 1) {
            return null;
        } else {
            return $this->exceptions[count($this->exceptions)-1];
        }
    }

    protected function lastResult()
    {
        if (count($this->results) < 1) {
            return null;
        } else {
            return $this->results[count($this->results)-1];
        }
    }

    protected function createLdapLinkWithConfig($config)
    {
        try {
            $this->ldap = Ldap::createWithConfig($config);
        } catch (\Exception $e) {
            $this->appendException($e);
        }
    }

    protected function bindWithArgs(...$args)
    {
        try {
            return $this->ldap->bind(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);
            return false;
        }
    }

    protected function queryWithArgs(...$args)
    {
        try {
            $result = $this->ldap->query(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);
            return false;
        }
        $this->appendResult($result);
        return $result;
    }

    public function decodeJsonPyStringNode(PyStringNode $pystring)
    {
        return json_decode($pystring->getRaw(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @Transform :config
     * @Transform :options
     */
    public function decodeJsonString($string)
    {
        return json_decode($string, true);
    }

    /**
     * @Given I am disconnected
     */
    public function iAmDisconnected()
    {
        if (isset($this->ldap)) {
            unset($this->ldap);
        }
    }

    /**
     * @Given I am connected to uri :uri
     */
    public function iAmConnectedToUri($uri)
    {
        $config = array('uri' => $uri);
        try {
            $this->ldap = Ldap::createWithConfig($config);
        } catch (\Exception $e) {
            $this->appendException($e);
        }
    }

    /**
     * @Given I am connected using config :config
     */
    public function iAmConnectedUsingConfig($config)
    {
        return $this->createLdapLinkWithConfig($config);
    }

    /**
     * @Given I am bound without arguments
     */
    public function iAmBoundWithoutArguments()
    {
        return $this->bindWithArgs();
    }

    /**
     * @Given I am bound with binddn :binddn
     */
    public function iAmBoundWithBindDn($binddn)
    {
        return $this->bindWithArgs($binddn);
    }

    /**
     * @Given I am bound with binddn :binddn and password :password
     */
    public function iAmBoundWithBindDnAndPassword($binddn, $password)
    {
        return $this->bindWithArgs($binddn, $password);
    }

    /**
     * @When I create ldap link with config :config
     */
    public function iCreateLdapLinkWithJsonConfig($config)
    {
        return $this->createLdapLinkWithConfig($config);
    }

    /**
     * @When I bind without arguments
     */
    public function iBindWithoutArguments()
    {
        return $this->bindWithArgs();
    }

    /**
     * @When I bind with binddn :binddn
     */
    public function iBindWithBindDn($binddn)
    {
        return $this->bindWithArgs($binddn);
    }

    /**
     * @When I bind with binddn :binddn and password :password
     */
    public function iBindWithBindDnAndPassword($binddn, $password)
    {
        return $this->bindWithArgs($binddn, $password);
    }

    /**
     * @When I query with basedn :basedn and filter :filter
     */
    public function iQueryWithBaseDnAndFilter($basedn, $filter)
    {
        return $this->queryWithArgs($basedn, $filter);
    }

    /**
     * @When I query with basedn :basedn, filter :filter and options :options
     */
    public function iQueryWithBaseDnFilterAndOptions($basedn, $filter, $options)
    {
        return $this->queryWithArgs($basedn, $filter, $options);
    }

    /**
     * @Then I should be bound
     */
    public function iShouldBeBound()
    {
        Assert::assertSame(true, $this->ldap->isBound());
    }

    /**
     * @Then I should see ldap exception with message :arg1
     */
    public function iShouldSeeLdapExceptionWithMessage($arg1)
    {
        $matchedExceptions = array_filter($this->exceptions, function ($e) use ($arg1) {
            return ($e instanceof LdapException) && $e->getMessage() == $arg1;
        });
        $expectedException = LdapException::class . '("' . $arg1 .'")';
        $foundExceptions = array_map(
            function ($e) {
                return get_class($e) . '("' . $e->getMessage() . '")';
            },
            $this->exceptions
        );
        $foundExceptionsStr = '[ ' . implode(', ', $foundExceptions) . ' ]';
        Assert::assertTrue(count($matchedExceptions) > 0, $expectedException . " not found in " . $foundExceptionsStr);
    }

    /**
     * @Then I should see ldap exception with code :arg1
     */
    public function iShouldSeeLdapExceptionWithCode($arg1)
    {
        Assert::assertInstanceOf(LdapException::class, $this->lastException());
        Assert::assertEquals($arg1, $this->lastException()->getCode());
    }

    /**
     * @Then I should see invalid options exception with message :arg1
     */
    public function iShouldSeeInvalidOptionsExceptionWithMessage($arg1)
    {
        Assert::assertInstanceOf(InvalidOptionsException::class, $this->lastException());
        Assert::assertEquals($arg1, $this->lastException()->getMessage());
    }

    /**
     * @Then I should see no exception
     */
    public function iShouldSeeNoException()
    {
        $e = $this->lastException();
        $msg = $e === null ? '' : "The last exception's message was: " . $e->getMessage();
        Assert::assertSame($e, null, $msg);
    }

    /**
     * @Then I should have a valid LDAP link
     */
    public function iShouldHaveAValidLdapLink()
    {
        Assert::assertInstanceOf(Ldap::class, $this->ldap);
        Assert::assertTrue($this->ldap->getBinding()->isLinkValid());
    }

    /**
     * @Then I should have no valid LDAP link
     */
    public function iShouldHaveNoValidLdapLink()
    {
        Assert::assertNull($this->ldap);
    }

    /**
     * @Then I should not be bound
     */
    public function iShouldNotBeBound()
    {
        Assert::assertFalse($this->ldap->isBound());
    }

    /**
     * @Then I should have last result entries
     */
    public function iShouldHaveLastResultEntries(PyStringNode $pystring)
    {
        $expected = $this->decodeJsonPyStringNode($pystring);
        $actual = array_map(
            function ($e) {
                return $e->getAttributes();
            },
            $this->lastResult()->getEntries()
        );

        # handle passwords
        foreach ($expected as $dn => $ee) {
            $expected_password = $ee['userpassword'][0] ?? null;
            $actual_password = $actual[$dn]['userpassword'][0] ?? null;
            if (is_string($expected_password) && is_string($actual_password)) {
                $expected[$dn]['userpassword'][0] = self::encryptExpectedPassword($expected_password, $actual_password);
            }
        }
        Assert::assertEquals($expected, $actual);
    }

    protected static function encryptExpectedPassword(string $expected_password, string $actual_password)
    {
        if (preg_match('/^\{([A-Z0-9]{3,5})\}(.+)$/', $actual_password, $matches)) {
            $tag = $matches[1];
            $actual_hash = $matches[2];
            if (strtoupper($tag) == 'CRYPT') {
                $expected_hash = crypt($expected_password, $actual_hash);
            } elseif (strtoupper($tag) == 'MD5') {
                $expected_hash = base64_encode(md5($expected_password, true));
            } elseif (strtoupper($tag) == 'SHA1') {
                $expected_hash =  base64_encode(sha1($expected_password, true));
            } elseif (strtoupper($tag) == 'SSHA') {
                $salt = substr(base64_decode($actual_hash), 20);
                $expected_hash = base64_encode(sha1($expected_password.$salt, true) . $salt);
            } else {
                throw new \RuntimeException("unsupported password hash format: $tag");
            }
            $expected_password = '{' . $tag . '}' . $expected_hash;
        }
        return $expected_password;
    }
}

// vim: syntax=php sw=4 ts=4 et:
