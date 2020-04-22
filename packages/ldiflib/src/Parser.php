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
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(ParserStateInterface $state) : bool
    {
        switch($this->config['file_type']) {
            case 'content':
                return $this->parseContentFile($state);
            case 'changes':
                return $this->parseChangesFile($state);
            case 'mixed':
                return $this->parseMixedFile($state);
            case 'detect':
                return $this->parseDetectFile($state);
            default:
                // FIXME: dedicated exception
                throw new \RuntimeException('internal error: wrong value of "file_type" option: '.var_export($this->config['file_type'], true));
        }
    }

    /**
     * @todo Write documentation
     */
    public function parseContentFile(ParserStateInterface $state) : bool
    {
        throw \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseChangesFile(ParserStateInterface $state) : bool
    {
        throw \BadMethodCallException('not implemented');
    }
    /**
     * @todo Write documentation
     */
    public function parseMixedFile(ParserStateInterface $state) : bool
    {
        throw \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseDetectFile(ParserStateInterface $state) : bool
    {
        throw \BadMethodCallException('not implemented');
    }

    /**
     * @todo Write documentation
     */
    public function parseVersionSpec(ParserStateInterface $state) : bool
    {
        $begin = $state->getCursor()->getClonedLocation();
        $tryOnly = !(($this->getConfig())['version_required'] ?? true);
        if (Parse::versionSpec($state, $version, $tryOnly)) {
            $length = $state->getCursor()->getOffset() - $begin->getOffset();
            $snippet = new Snippet($begin, $length);
            $versionSpec = new VersionSpec($snippet, $version);
            $state->setVersionSpec($versionSpec);
            return true;
        }
        $state->setVersionSpec(null);
        return empty($state->getErrors());
    }

    /**
     * @todo Write documentation
     */
    public function parseContentRecord(ParserStateInterface $state) : bool
    {
    }

    /**
     * @todo Write documentation
     */
    public function parseChangeRecord(ParserStateInterface $state) : bool
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
