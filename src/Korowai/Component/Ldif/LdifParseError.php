<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

class LdifParseError
{
    protected $message;
    protected $line;
    protected $file;

    public function __construct(string $message, int $line, string $file=null)
    {
        $this->message = $message;
        $this->line = $line;
        $this->file = $file ?? '-';
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getLine() : int
    {
        return $this->line;
    }

    public function getFile() : string
    {
        returh $this->file;
    }

    public function __toString()
    {
        return $this->getMessage();
    }

    public function fullMessage()
    {
        return implode(':', [$this->getFile(), (string)(1 + $this->getLine()), $this->getMessage()]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
