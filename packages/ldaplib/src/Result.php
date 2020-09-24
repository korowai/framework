<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use function Korowai\Lib\Context\with;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapResultEntryIterator;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceIterator;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperTrait;

/**
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Result implements ResultInterface, LdapResultWrapperInterface
{
    use LdapResultWrapperTrait;

    /**
     * Initializes the object.
     */
    public function __construct(LdapResultInterface $ldapResult)
    {
        $this->ldapResult = $ldapResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntryIterator(): ResultEntryIteratorInterface
    {
        return new ResultEntryIterator(
            $this->getResultItemIterator('first_entry', LdapResultEntryIterator::class)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferenceIterator(): ResultReferenceIteratorInterface
    {
        return new ResultReferenceIterator(
            $this->getResultItemIterator('first_reference', LdapResultReferenceIterator::class)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntries(): array
    {
        return iterator_to_array($this->getResultEntryIterator(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferences(): array
    {
        return iterator_to_array($this->getResultReferenceIterator(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries(bool $use_keys = true): array
    {
        return iterator_to_array($this, $use_keys);
    }

    /**
     * Makes the ``Result`` object iterable.
     */
    public function getIterator()
    {
        $iterator = $this->getResultEntryIterator();
        foreach ($iterator as $key => $entry) {
            if (null === $entry) {
                $message = sprintf('Null returned by %s::current() during iteration', get_class($iterator));

                throw new \UnexpectedValueException($message);
            }
            yield $key => $entry->toEntry();
        }
    }

    /**
     * @psalm-template T
     * @psalm-param class-string<T> $class
     * @psalm-return T
     */
    private function getResultItemIterator(string $method, string $class): object
    {
        $result = $this->getLdapResult();
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(
            /** @return false|LdapResultItemInterface */
            function () use ($result, $method) {
                return $result->{$method}();
            }
        );

        return new $class($first ?: null, $first ?: null, 0);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
