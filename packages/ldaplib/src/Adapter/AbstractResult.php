<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResult implements ResultInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntries(bool $use_keys = true) : array
    {
        return iterator_to_array($this, $use_keys);
    }

    /**
     * Makes the ``Result`` object iterable
     */
    public function getIterator()
    {
        $iterator = $this->getResultEntryIterator();
        foreach ($iterator as $key => $entry) {
            if ($entry === null) {
                $message = sprintf("Null returned by %s::current() during iteration", get_class($iterator));
                throw new \UnexpectedValueException($message);
            }
            yield $key => $entry->toEntry();
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
