<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ValueObject\Option;

return
/**
 * @psalm-param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
 */
static function ($container) : void {
    $parameters = $container->parameters();

    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__.'/packages',
    ]);

    $parameters->set(Option::SECTION_ORDER, [
        'name',
        'type',
        'description',
        'license',
        'keywords',
        'homepage',
        'support',
        'authors',
        'minimum-stability',
        'prefer-stable',
        'bin',
        'require',
        'require-dev',
        'autoload',
        'autoload-dev',
        'repositories',
        'conflict',
        'replace',
        'provide',
        'scripts',
        'suggest',
        'config',
        'extra',
    ]);


    $parameters->set(Option::DATA_TO_APPEND, [
        'require-dev' => [
            'symplify/monorepo-builder' => '^8.0.1',
            'phpunit/phpunit' => '>=9.3',
            'php-mock/php-mock-phpunit' => '>=2.4.0',
            'phake/phake' => '^3.0',
            'behat/behat' => '^3.4',
            'friendsofphp/php-cs-fixer' => '^2.16',
            'psy/psysh' => 'dev-master',
        ],
        'autoload-dev' => [
            'psr-4' => [
                'Korowai\\Docs\\Behat\\' => 'docs/sphinx/behat/',
            ],
        ],
    ]);

    $packages = [
        'basiclib',
        'basiclib-interfaces',
        'compatlib',
        'contextlib',
        'contextlib-interfaces',
        'contracts',
        'errorlib-interfaces',
        'ldaplib',
        'ldaplib-interfaces',
        'ldiflib',
        'ldiflib-interfaces',
        'rfclib',
        'rfclib-interfaces',
        'testing',
    ];

    $packagesDirBase  = 'packages/';
    $packagesDirs  = \preg_replace('/^/', $packagesDirBase,  $packages);

    $defaultSplitRepositoryBase = 'file://'.__dir__.'/build/monorepo-split/repositories/korowai';
    $parameters->set('default_split_repository_base', $defaultSplitRepositoryBase);
    $splitRepositoryBase = '%env(default:default_split_repository_base:MONOREPO_SPLIT_REPO_BASE)%/';

    $packageRepos = \preg_replace('/^(.+)$/', $splitRepositoryBase.'\1.git', $packages);
    $directoriesToRepositories = array_combine($packagesDirs, $packageRepos);

    $parameters->set(Option::DIRECTORIES_TO_REPOSITORIES, $directoriesToRepositories);
};
