<?php
/**
 * @file src/Traits/ParsesValueSpec.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\Rfc8089;
use function Korowai\Lib\Compat\preg_match;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesValueSpec
{
    abstract public function parseBase64Decode(State $state, string $string, ?int $offset = null) : ?string;

    /**
     * Completes value-spec parsing (the Rfc2849x::VALUE_SPEC_X rule).
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $valueSpec
     *
     * @return bool
     */
    protected function parseMatchedValueSpec(State $state, array $matches, array &$valueSpec = null) : bool
    {
        if (static::captured('value_b64', $matches, $string, $offset)) {
            $valueSpec['value_b64'] = $string;
            $decoded = $this->parseBase64Decode($state, $string, $offset);
            $valueSpec['value'] = $decoded;
            return ($decoded !== null);
        } elseif (static::captured('value_safe', $matches, $string, $offset)) {
            $valueSpec['value_safe'] = $string;
            $valueSpec['value'] = $string;
            return true;
        } elseif (static::captured('value_url', $matches, $string, $offset)) {
            $valueSpec['value_url'] = $string;
            return $this->parseMatchedUriReference($state, $matches, $valueSpec);
        }

        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
        $state->errorHere($message);
        $valueSpec = null;
        return false;
    }

    /**
     * Completes value-spec parsing (the Rfc2849x::VALUE_SPEC_X rule) when
     * matched Rfc3986::URI_REFERENCE type.
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $valueSpec
     *
     * @return bool
     */
    protected function parseMatchedUriReference(State $state, array $matches, array &$valueSpec = null) : bool
    {
        if (static::captured('uri', $matches, $string, $offset)) {
            return $this->parseMatchedUri($state, $matches, $valueSpec);
        } elseif (static::captured('relative_ref', $matches, $string, $offset)) {
            return $this->parseMatchedRelativeRef($state, $matches, $valueSpec);
        }

        $state->errorHere('internal error: missing or invalid capture groups "uri" and "relative_ref"');
        $valueSpec = null;
        return false;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedUri(State $state, array $matches, array &$valueSpec = null) : bool
    {
        $valueSpec['uri'] = static::getCapturedValues(Rfc3986::class, 'URI', $matches);

        if (static::captured('scheme', $matches, $schemeString, $schemeOffset)) {
            static::captured('uri', $matches, $uri, $offset);
            switch ($schemeString) {
                case 'file':
                    return $this->parseMatchedFileUri($state, $uri, $offset, $valueSpec);
                default:
                    $state->errorAt($schemeOffset, 'syntax error: unsupported URI scheme "'.$schemeString.'"');
                    //$valueSpec = null;
                    return false;
            }
        }

        $state->errorHere('internal error: missing or invalid capture group "scheme"');
        $valueSpec = null;
        return false;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedRelativeRef(State $state, array $matches, array &$valueSpec = null) : bool
    {
        $valueSpec['relative_ref'] = static::getCapturedValues(Rfc3986::class, 'RELATIVE_REF', $matches);
        return true;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedFileUri(State $state, string $string, int $offset, array &$valueSpec = null) : bool
    {
        $re = '/\G'.Rfc8089::FILE_URI.'$/';
        if (0 === preg_match($re, $string, $matches, PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL)) {
            $state->errorAt($offset, 'syntax error: invalid syntax for file URI');
            return false;
        }
        $valueSpec['file_uri'] = static::getCapturedValues(Rfc8089::class, 'FILE_URI', $matches);
        return true;
    }

    /**
     * Returns true if *$matches* contain non-null capture identified by *$key*.
     *
     * @param  mixed $key Key identifying the capture group.
     * @param  array $matches The array of matches as returned by ``preg_match()``.
     * @param  string $string Returns the captured string  (or null).
     * @param  int $offset Returns the capture offset (or -1).
     *
     * @return bool Returns true if there is non-null capture group under *$key*.
     */
    public static function captured($key, array $matches, string &$string = null, int &$offset = null) : bool
    {
        return (($offset = $matches[$key][1] ?? -1) !== -1 && ($string = $matches[$key][0] ?? null) !== null);
    }

    /**
     * @todo Write documentation.
     */
    public static function getCapturedValues(string $class, string $ruleName, array $matches) : array
    {
        return static::getCapturedStrings($class::findCapturedValues($ruleName, $matches));
    }

    /**
     * @todo Write documentation.
     */
    public static function getCapturedStrings(array $matches) : array
    {
        return array_map(function (array $match) {
            return $match[0];
        }, $matches);
    }
}

// vim: syntax=php sw=4 ts=4 et:
