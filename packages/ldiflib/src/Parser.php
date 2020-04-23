<?php
/**
 * @file src/Parser.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Rfc\Rfc2849;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * LDIF parser.
 */
class Parser implements ParserInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Returns the name of the records parsing method for the given
     * *$fileType*. Throws an exception for unsupported file type.
     *
     * Supported file types are:
     *
     * - ``'content'``,
     * - ``'changes'``,
     * - ``'mixed'``, and
     * - ``'detect'``.
     *
     * @param string $fileType
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getRecordParserMethod(string $fileType) : string
    {
        $methods = [
            'content' => 'parseContentRecord',
            'changes' => 'parseChangesRecord',
            'mixed'   => 'parseMixedRecord',
            'detect'  => 'parseDetectRecord',
        ];
        if (($method = $methods[$fileType] ?? null) === null) {
            throw new \RuntimeException('internal error: invalid file type: "'.$type.'"');
        }
        return $method;
    }

    /**
     * Initializes the parser
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    /**
     * Configure the parser with configuration options.
     *
     * @param array $config
     *
     * @return object $this
     */
    public function configure(array $config)
    {
        $this->config = $this->resolveConfig($config);
        return $this;
    }

    /**
     * Return configuration array previously set with configure().
     *
     * If configuration is not set yet, null is returned.
     *
     * @return array|null
     */
    public function getConfig() : ?array
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(ParserStateInterface $state) : bool
    {
        $prevErrCount = count($state->getErrors());
        $tryOnly = !(($this->getConfig())['version_required'] ?? true);
        $success = $this->parseVersionSpec($state, $tryOnly);
        if (!$success && (count($state->getErrors()) > $prevErrCount)) {
            return false;
        }
        if ($success && !$this->parseSeps($state)) {
            return false;
        }
        return $this->parseRecords($state);
    }

    /**
     * @todo Write documentation
     */
    public function parseRecords(ParserStateInterface $state) : bool
    {
        $fileType = ($this->getConfig())['file_type'];
        $method = $this->getRecordParserMethod($fileType);

        $cursor = $state->getCursor();
        $endOffset = strlen($cursor->getString());

        //
        // ldif-foo-record *(1*SEP ldif-foo-record)
        //
        if (!call_user_func([$this, $method], $state)) {
            return false;
        }

        while ($this->parseSeps($state, true)) {
            if (($cursor->getOffset() < $endOffset) && !call_user_func([$this, $method], $state)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseSeps(ParserStateInterface $state, bool $tryOnly = false) : bool
    {
        if (!Parse::sep($state, $sep, $tryOnly)) {
            return false;
        }
        while (Parse::sep($state, $sep, true)) {
        }
        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseVersionSpec(ParserStateInterface $state, bool $tryOnly = false) : bool
    {
        $begin = $state->getCursor()->getClonedLocation();
        if (!Parse::versionSpec($state, $version, $tryOnly)) {
            return false;
        }
        $length = $state->getCursor()->getOffset() - $begin->getOffset();
        $snippet = new Snippet($begin, $length);
        $versionSpec = new VersionSpec($snippet, $version);
        $state->setVersionSpec($versionSpec);
        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseContentRecord(ParserStateInterface $state, bool $tryOnly = false) : bool
    {
        $begin = $state->getCursor()->getClonedLocation();

        //
        // dn-spec SEP 1*attrval-spec
        //
        if (!Parse::dnSpec($state, $dn, $tryOnly) ||
            !Parse::sep($state) ||
            !Parse::attrValSpec($state, $attrValSpec)) {
            return false;
        }

        $attrValSpecs[] = $attrValSpec;

        while (Parse::sep($state, $sep, true) && Parse::attrValSpec($state, $attrValSpec, true)) {
            $attrValSpecs[] = $attrValSpec;
        }

        $offset = $state->getCursor()->getOffset();
        $snippet = new Snippet($begin, $offset - $begin->getOffset());
        $record = new AttrValRecord($snippet, $dn, $attrValSpecs);
        $state->appendRecord($record);

        return true;
    }

    /**
     * @todo Write documentation
     */
    public function parseChangeRecord(ParserStateInterface $state) : bool
    {
    }

    /**
     * @todo Write documentation
     */
    public function parseMixedRecord(ParserStateInterface $state) : bool
    {
    }

    /**
     * @todo Write documentation
     */
    public function parseDetectRecord(ParserStateInterface $state) : bool
    {
    }

    /**
     * Validate and resolve configuration options for the Parser.
     *
     * @param array $config Input config options.
     *
     * @return array returns the array of resolved config options.
     */
    protected function resolveConfig(array $config) : array
    {
        $resolver = new OptionsResolver;
        $this->configureOptionsResolver($resolver);
        return $resolver->resolve($config);
    }

    /**
     * Configures OptionsResolver for this Parser
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    protected function configureOptionsResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'file_type' => 'content',
            'version_required' => false,
        ]);

        $resolver->setAllowedValues('file_type', ['content', 'changes', 'mixed', 'detect']);
        $resolver->setAllowedTypes('version_required', 'bool');
    }
}

// vim: syntax=php sw=4 ts=4 et:
