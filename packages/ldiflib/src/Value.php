<?php
/**
 * @file src/Value.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Rfc\Rfc3986;
use League\Uri\Uri;
use League\Uri\Contracts\UriInterface;
use League\Uri\Exceptions\SyntaxError;
use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\exceptionErrorHandler;

/**
 * Semantic value of RFC2849 ``value-spec`` rule. One of SAFE-STRING,
 * BASE64-STRING or URL.
 */
final class Value implements ValueInterface
{
    /**
     * Type enum for the RFC2849 SAFE-STRING value.
     */
    public const TYPE_SAFE = 0;

    /**
     * Type enum for the RFC2849 BASE64-STRING value.
     */
    public const TYPE_BASE64 = 1;

    /**
     * Type enum for the RFC2849 URL value.
     */
    public const TYPE_URL = 2;

    /**
     * Maps ``TYPE_*`` integers to their names.
     */
    private const TYPE_NAMES = [
        self::TYPE_SAFE     => 'SAFE-STRING',
        self::TYPE_BASE64   => 'BASE64-STRING',
        self::TYPE_URL      => 'URL',
    ];

    /**
     * @var int
     */
    private $type;

    /**
     * @var string|UriInterface
     */
    private $value;

    /**
     * @var string
     */
    private $content;

    /**
     * Creates Value from SAFE-STRING.
     *
     * @param  string $string
     * @return ValueInterface
     */
    public static function createSafeString(string $string) : ValueInterface
    {
        return new self(self::TYPE_SAFE, $string);
    }

    /**
     * Creates Value from BASE64-STRING.
     *
     * @param  string $base64string
     * @param  string $decoded
     * @return ValueInterface
     */
    public static function createBase64String(string $base64string, string $decoded = null) : ValueInterface
    {
        return new self(self::TYPE_BASE64, $base64string, $decoded);
    }

    /**
     * Creates Value from preg matches obtained by parsing an URL. It's
     * assumed, that matches were obtained from ``preg_match()`` invoked with
     * PREG_OFFSET_CAPTURE flag.
     *
     * @param  array $matches
     * @return ValueInterface
     * @throws SyntaxError
     *      if the URI is in an invalid state according to RFC3986 or to scheme
     *      specific rules.
     */
    public static function createUriFromRfc3986Matches(array $matches) : ValueInterface
    {
        $matches = Rfc3986::findCapturedValues('URI_REFERENCE', $matches);

        $components = array_combine(array_keys($matches), array_column($matches, 0));

        if (null !== ($components['userinfo'] ?? null)) {
            [$user, $pass] = explode(':', $components['userinfo'], 2) + [1 => null];
            $components['user'] = $user;
            $components['pass'] = $pass;
        }

        if (null !== ($components['port'] ?? null)) {
            // FIXME: check whether it's an integer?
            $components['port'] = intval($components['port']);
        }

        $components['path'] =
            $components['path_abempty'] ??
            $components['path_absolute'] ??
            $components['path_rootless'] ??
            $components['path_noscheme'] ??
            $components['path_empty'] ?? null;

        return self::createUriFromComponents($components);
    }

    /**
     * Creates Value form components that represent an URI.
     *
     * @param  array $components
     * @return ValueInterface
     */
    public static function createUriFromComponents(array $components) : ValueInterface
    {
        $uri = Uri::createFromComponents($components);
        return static::createUri($uri);
    }

    /**
     * Creates Value from Uri object.
     *
     * @param  Uri $uri
     * @return ValueInterface
     */
    public static function createUri(UriInterface $uri) : ValueInterface
    {
        return new self(self::TYPE_URL, $uri);
    }

    /**
     * Initializes the Value object.
     *
     * @param  int $type
     *      Must be one of ``Value::TYPE_SAFE``, ``Value::TYPE_BASE64`` or ``Value::TYPE_URL``.
     * @param  mixed $value
     *      The value to be encapsulated. Must be a string when *$type* is
     *      ``Value::TYPE_SAFE`` or ``Value::TYPE_BASE64``, or /League\Uri\Uri
     *      when *$type* is ``Value::URL``.
     * @param  string $content
     *      Actual content. When *$type* is ``Value::TYPE_BASE64`` the
     *      *$content* should contain decoded string. When *$type* is
     *      ``Value::TYPE_URL``, then *$content* should contain the contents of
     *      the file pointed to by the *$value* URI.
     */
    private function __construct(int $type, $value, string $content = null)
    {
        $this->type = $type;
        $this->value = $value;
        $this->content = $content;
    }

    /**
     * Returns the type of this Value.
     *
     * @return int
     *      Returns one of ``TYPE_SAFE``, ``TYPE_BASE64`` or ``TYPE_URL``.
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Return the original value as provided to constructor. The returned value
     * may be either safe string, base64-encoded string or an object
     * that encapsulates an URI.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent() : string
    {
        if (!isset($this->content)) {
            $this->content = static::fetchContent($this->getType(), $this->getValue());
        }
        return $this->content;
    }

    /**
     * Returns the content for *$value* of given *$type*.
     *
     * @param  int $type
     * @param  mixed $value
     * @return string
     */
    private static function fetchContent(int $type, $value) : string
    {
        switch ($type) {
            case static::TYPE_SAFE:
                $content = $value;
                break;
            case static::TYPE_BASE64:
                $content = static::fetchBase64Content($value);
                break;
            case static::TYPE_URL:
                $content = static::fetchUriContent($value);
                break;
            default:
                // @codeCoverageIgnoreStart
                // FIXME: dedicated exception.
                throw new \RuntimeException('internal error: invalid type set to Value obejct');
                // @codeCoverageIgnoreEnd
        }
        return $content;
    }

    /**
     * Decodes base64-encoded *$value*.
     *
     * @param  string $value
     * @return string
     */
    private static function fetchBase64Content(string $value) : string
    {
        $content = base64_decode($value, true);
        if ($content === false) {
            // FIXME: dedicated exception.
            throw new \RuntimeException('failed to decode base64 string');
        }
        return $content;
    }

    /**
     * Retrieves content referenced by *$uri*.
     *
     * @param  UriInterface $uri
     * @return string
     */
    private static function fetchUriContent(UriInterface $uri) : string
    {
        // FIXME: dedicated exception?
        return with(exceptionErrorHandler(\ErrorException::class), $uri)(function ($eh, $uri) {
            return file_get_contents((string)$uri);
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
