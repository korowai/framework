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

/**
 * LDIF parse error. Encapsulates error message and the location of the error
 * in source code.
 */
class ParseError implements SourceLocationInterface
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var SourceLocationInterface
     */
    protected $location;


    /**
     * Initializes the error object.
     *
     * @param string $message Error message.
     * @param SourceLocationInterface $location Error location
     */
    public function __construct(string $message, SourceLocationInterface $location)
    {
        $this->message = $message;
        $this->location = $location;
    }

    /**
     * Returns the error message provided to constructor as $message
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * Returns the cursor pointing at error location.
     *
     * @return int
     */
    public function getLocation() : SourceLocationInterface
    {
        return $this->location;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource() : string
    {
        return $this->getLocation()->getSource();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteOffset() : int
    {
        return $this->getLocation()->getSourceByteOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharOffset(string $encoding = null) : int
    {
        return $this->getLocation()->getSourceCharOffset(...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineIndex() : int
    {
        return $this->getLocation()->getSourceLineIndex();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLine(int $index=null) : string
    {
        return $this->getLocation()->getSourceLine(...func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndByte() : array
    {
        return $this->getLocation()->getSourceLineAndByte();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndChar(string $encoding=null) : array
    {
        return $this->getLocation()->getSourceLineAndChar(...(func_get_args()));
    }


    /**
     * {@inheritdoc}
     */
    public function getSourceFileName() : string
    {
        return $this->getLocation()->getSourceFileName();
    }

    /**
     * Returns the error location as a string.
     *
     * @return string
     */
    public function getSourceLocationString(array $line_and_char=null) : string
    {
        [$line, $char] = ($line_and_char ?? $this->getSourceLineAndChar());
        return  $this->getSourceFileName() .':'. ($line + 1) .':'. ($char + 1);
    }

    /**
     * Returns a string which consists of a number of leading spaces and the
     * ``"^"`` character.
     *
     * @param array $line_and_char
     *
     * The position of the ``"^"`` character corresponds to the error location
     * in the source line. The typical use of the function is as
     *
     * ```
     * <?php
     * $err = new ParseError("syntax error", ...);
     * $lines = [
     *      $err->getMessage(),
     *      $err->getSourceLine(),
     *      $err->getSourceLocationIndicator()
     * ];
     * echo implode("\n", $lines) . "\n";
     * ```
     *
     * @return string
     */
    public function getSourceLocationIndicator(array $line_and_char=null)
    {
        $char = ($line_and_char ?? $this->getSourceLineAndChar())[1];
        return str_repeat(' ', $char) . '^';
    }

    /**
     * Returns 3-element array of strings - elements of a multiline error message.
     *
     * Example usage:
     *
     * ```php
     * <?php
     *  $err = new ParseError("syntax error", ...);
     *  echo implode("\n", $err->getMultilineMessageArray()) . "\n";
     * ```
     *
     * @return array A 3-element array of strings, with error message at
     *               position 0, source line at position 1 and location
     *               indicator at position 2.
     */
    public function getMultilineMessageLines() : array
    {
        $line_and_char = $this->getSourceLineAndChar();
        $location = $this->getSourceLocationString($line_and_char);
        return [
            $location .':'. $this->getMessage($line_and_char),
            $location .':'. $this->getSourceLine($line_and_char[0]),
            $location .':'. $this->getSourceLocationIndicator($line_and_char)
        ];
    }

    /**
     * Returns multiline error message for detailed error reporting.
     *
     * @return string
     */
    public function getMultilineMessage() : string
    {
        return implode("\n", $this->getMultilineMessageLines());
    }
}

// vim: syntax=php sw=4 ts=4 et:
