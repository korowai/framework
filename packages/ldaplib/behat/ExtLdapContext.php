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
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\LdapException;
use Korowai\Lib\Ldap\Core\LdapLink;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Testing\Ldaplib\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Psr\Container\ContainerInterface;

/**
 * Defines application features from the specific context.
 */
class ExtLdapContext implements Context
{
    use LdapHelperTrait;
    use ExceptionLogTrait;
    use ResultLogTrait;
    use CommonHelpersTrait;

    /**
     * @var ContainerInterface;
     */
    private $container;

    /**
     * @var LdapInterface|null
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

        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions(\Korowai\Ldaplib\config_path('php-di/services.php'));
        $this->container = $builder->build();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getLdap(): ?LdapInterface
    {
        return $this->ldap;
    }

    protected function setLdap(?LdapInterface $ldap): void
    {
        $this->ldap = $ldap;
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
     *
     * @param mixed $string
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
     *
     * @param mixed $uri
     */
    public function iAmConnectedToUri($uri)
    {
        $config = ['uri' => $uri];
        $this->createLdapLinkWithConfig($config);
    }

    /**
     * @Given I am connected using config :config
     *
     * @param mixed $config
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
     *
     * @param mixed $binddn
     */
    public function iAmBoundWithBindDn($binddn)
    {
        return $this->bindWithArgs($binddn);
    }

    /**
     * @Given I am bound with binddn :binddn and password :password
     *
     * @param mixed $binddn
     * @param mixed $password
     */
    public function iAmBoundWithBindDnAndPassword($binddn, $password)
    {
        return $this->bindWithArgs($binddn, $password);
    }

    /**
     * @When I create ldap link with config :config
     *
     * @param mixed $config
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
     *
     * @param mixed $binddn
     */
    public function iBindWithBindDn($binddn)
    {
        return $this->bindWithArgs($binddn);
    }

    /**
     * @When I bind with binddn :binddn and password :password
     *
     * @param mixed $binddn
     * @param mixed $password
     */
    public function iBindWithBindDnAndPassword($binddn, $password)
    {
        return $this->bindWithArgs($binddn, $password);
    }

    /**
     * @When I search with basedn :basedn and filter :filter
     *
     * @param mixed $basedn
     * @param mixed $filter
     */
    public function iSearchWithBaseDnAndFilter($basedn, $filter)
    {
        return $this->searchWithArgs($basedn, $filter);
    }

    /**
     * @When I search with basedn :basedn, filter :filter and options :options
     *
     * @param mixed $basedn
     * @param mixed $filter
     * @param mixed $options
     */
    public function iSearchWithBaseDnFilterAndOptions($basedn, $filter, $options)
    {
        return $this->searchWithArgs($basedn, $filter, $options);
    }

    /**
     * @When I compare dn :dn, attribute :attribute with value :value
     *
     * @param mixed $dn
     * @param mixed $attribute
     * @param mixed $value
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
     * @Then I should see ldap LdapException with message :arg1
     *
     * @param mixed $arg1
     */
    public function iShouldSeeLdapLdapExceptionWithMessage($arg1)
    {
        $matchedExceptions = array_filter($this->exceptions, function ($e) use ($arg1) {
            return ($e instanceof LdapException) && $e->getMessage() == $arg1;
        });
        $expectedException = LdapException::class.'("'.$arg1.'")';
        $foundExceptions = array_map(
            function ($e) {
                return get_class($e).'("'.$e->getMessage().'")';
            },
            $this->exceptions
        );
        $foundExceptionsStr = '[ '.implode(', ', $foundExceptions).' ]';
        TestCase::assertTrue(count($matchedExceptions) > 0, $expectedException.' not found in '.$foundExceptionsStr);
    }

    /**
     * @Then I should see ldap LdapException with code :arg1
     *
     * @param mixed $arg1
     */
    public function iShouldSeeLdapLdapExceptionWithCode($arg1)
    {
        TestCase::assertInstanceOf(LdapException::class, $this->lastException());
        TestCase::assertEquals($arg1, $this->lastException()->getCode());
    }

    /**
     * @Then I should see ldap ErrorException with message :arg1
     *
     * @param mixed $arg1
     */
    public function iShouldSeeLdapErrorExceptionWithMessage($arg1)
    {
        $matchedExceptions = array_filter($this->exceptions, function ($e) use ($arg1) {
            return ($e instanceof ErrorException) && $e->getMessage() == $arg1;
        });
        $expectedException = ErrorException::class.'("'.$arg1.'")';
        $foundExceptions = array_map(
            function ($e) {
                return get_class($e).'("'.$e->getMessage().'")';
            },
            $this->exceptions
        );
        $foundExceptionsStr = '[ '.implode(', ', $foundExceptions).' ]';
        TestCase::assertTrue(count($matchedExceptions) > 0, $expectedException.' not found in '.$foundExceptionsStr);
    }

    /**
     * @Then I should see invalid options exception with message :arg1
     *
     * @param mixed $arg1
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
        $msg = null === $e ? '' : "The last exception's message was: ".$e->getMessage();
        TestCase::assertSame($e, null, $msg);
    }

    /**
     * @Then I should have a valid LDAP link
     */
    public function iShouldHaveAValidLdapLink()
    {
        TestCase::assertInstanceOf(Ldap::class, $this->ldap);
        /** @var LdapLink */
        $link = $this->ldap->getLdapLink();
        TestCase::assertInstanceOf(LdapLink::class, $link);
        TestCase::assertTrue($link->isValid());
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
            function ($entry): array {
                return [
                    'dn' => $entry->getDn(),
                    'attributes' => $entry->getAttributes(),
                ];
            },
            $this->lastResult()->getEntries()
        );

        $comparator = function (array $left, array $right): int {
            return strcmp($left['dn'] ?? '', $right['dn'] ?? '');
        };

        usort($expectedEntries, $comparator);
        usort($actualEntries, $comparator);

        // handle passwords
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

// vim: syntax=php sw=4 ts=4 et:
