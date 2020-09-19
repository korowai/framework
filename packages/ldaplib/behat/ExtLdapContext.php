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

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\Core\Adapter as ExtLdapAdapter;
use Korowai\Lib\Ldap\Exception\LdapException;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * Defines application features from the specific context.
 */
class ExtLdapContext implements Context
{
    use LdapHelper, CommonHelpers;

    /**
     * @var Ldap
     */
    private $ldap;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->initLdapHelper();
    }

    /**
     * Clear and initialize data in the ldap database.
     *
     * @BeforeScenario @initDbBeforeScenario
     * @BeforeSuite @initDbBeforeSuite
     * @BeforeFeature @initDbBeforeFeature
     * @AfterScenario @initDbAfterScenario
     * @AfterSuite @initDbAfterSuite
     * @AfterFeature @initDbAfterFeature
     */
    public static function initDb()
    {
        $db = LdapService::getInstance();
        $db->deleteAllData();
        $db->addFromLdifFile(__DIR__.'/../resources/ldif/bootstrap.ldif');
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
        return json_decode($string, true, 512, JSON_THROW_ON_ERROR);
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
        $config = ['uri' => $uri];
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
     * @When I search with basedn :basedn and filter :filter
     */
    public function iSearchWithBaseDnAndFilter($basedn, $filter)
    {
        return $this->searchWithArgs($basedn, $filter);
    }

    /**
     * @When I search with basedn :basedn, filter :filter and options :options
     */
    public function iSearchWithBaseDnFilterAndOptions($basedn, $filter, $options)
    {
        return $this->searchWithArgs($basedn, $filter, $options);
    }

    /**
     * @When I compare dn :dn, attribute :attribute with value :value
     */
    public function iCompareDnAttributeAndValue($dn, $attribute, $value)
    {
        return $this->compareWithArgs($dn, $attribute, $value);
    }

    /**
     * @Then I should be bound
     */
    public function iShouldBeBound()
    {
        TestCase::assertSame(true, $this->ldap->isBound());
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
        TestCase::assertTrue(count($matchedExceptions) > 0, $expectedException . " not found in " . $foundExceptionsStr);
    }

    /**
     * @Then I should see ldap exception with code :arg1
     */
    public function iShouldSeeLdapExceptionWithCode($arg1)
    {
        TestCase::assertInstanceOf(LdapException::class, $this->lastException());
        TestCase::assertEquals($arg1, $this->lastException()->getCode());
    }

    /**
     * @Then I should see invalid options exception with message :arg1
     */
    public function iShouldSeeInvalidOptionsExceptionWithMessage($arg1)
    {
        TestCase::assertInstanceOf(InvalidOptionsException::class, $this->lastException());
        TestCase::assertEquals($arg1, $this->lastException()->getMessage());
    }

    /**
     * @Then I should see no exception
     */
    public function iShouldSeeNoException()
    {
        $e = $this->lastException();
        $msg = $e === null ? '' : "The last exception's message was: " . $e->getMessage();
        TestCase::assertSame($e, null, $msg);
    }

    /**
     * @Then I should have a valid LDAP link
     */
    public function iShouldHaveAValidLdapLink()
    {
        TestCase::assertInstanceOf(Ldap::class, $this->ldap);
        /** @var ExtLdapAdapter */
        $adapter = $this->ldap->getAdapter();
        TestCase::assertInstanceOf(ExtLdapAdapter::class, $adapter);
        TestCase::assertTrue($adapter->getLdapLink()->isValid());
    }

    /**
     * @Then I should have no valid LDAP link
     */
    public function iShouldHaveNoValidLdapLink()
    {
        TestCase::assertNull($this->ldap);
    }

    /**
     * @Then I should not be bound
     */
    public function iShouldNotBeBound()
    {
        TestCase::assertFalse($this->ldap->isBound());
    }

    /**
     * @Then I should have last result entries
     */
    public function iShouldHaveLastResultEntries(PyStringNode $pystring)
    {
        $expectedEntries = $this->decodeJsonPyStringNode($pystring);
        $actualEntries = array_map(
            function ($entry) : array {
                return [
                    'dn' => $entry->getDn(),
                    'attributes' => $entry->getAttributes()
                ];
            },
            $this->lastResult()->getEntries()
        );

        $comparator = function (array $left, array $right) : int {
            return strcmp($left['dn'] ?? '', $right['dn'] ?? '');
        };

        usort($expectedEntries, $comparator);
        usort($actualEntries, $comparator);

        # handle passwords
        foreach ($expectedEntries as $i => $ee) {
            $expectedPassword = $ee['attributes']['userpassword'][0] ?? null;
            $actualPassword = $actualEntries[$i]['attributes']['userpassword'][0] ?? null;
            if (is_string($expectedPassword) && is_string($actualPassword)) {
                $encrypted = self::encryptForComparison($expectedPassword, $actualPassword);
                $expectedEntries[$i]['attributes']['userpassword'][0] = $encrypted;
            }
        }
        TestCase::assertEquals($expectedEntries, $actualEntries);
    }

    /**
     * @Then I should have last result references
     */
    public function iShouldHaveLastResultReferences(PyStringNode $pystring)
    {
        $expectedReferences = $this->decodeJsonPyStringNode($pystring);
        $actualReferences = array_map(
            function ($e) {
                return $e->getReferrals();
            },
            iterator_to_array($this->lastResult()->getResultReferenceIterator())
        );
        TestCase::assertEquals($expectedReferences, $actualReferences);
    }

    /**
     * @Then I should have last result true
     */
    public function iShouldHaveLastResultTrue()
    {
        TestCase::assertTrue($this->lastResult());
    }

    /**
     * @Then I should have last result false
     */
    public function iShouldHaveLastResultFalse()
    {
        TestCase::assertFalse($this->lastResult());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
