<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ValueObject\Option;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// USAGE:
//
// 1. Split onto repositories under "build/monorepo-split/repositories/korowai/"
//
//      vendor/bin/monorepo-builder split
//
//    Bare repository are required to be present uner this base path before the
//    split is performed. To initialize these repositories run
//
//      util/initialize-split-repositories.sh
//
// 2. Split onto repositories under git@github.com:korowai/
//
//      MONOREPO_SPLIT_REPO_BASE='git@github.com:korowai' vendor/bin/monorepo-builder split
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    $packagesComposerJsonFiles = glob('packages/*/composer.json');
    $packages = \preg_replace('/^packages\/([^\/]+)\/composer.json$/', '\1', $packagesComposerJsonFiles);

    $packagesDirBase  = 'packages/';
    $packagesDirs  = \preg_replace('/^/', $packagesDirBase,  $packages);

    $defaultSplitRepositoryBase = 'file://'.__dir__.'/build/monorepo-split/repositories/korowai';
    $parameters->set('default_split_repository_base', $defaultSplitRepositoryBase);
    $splitRepositoryBase = '%env(default:default_split_repository_base:MONOREPO_SPLIT_REPO_BASE)%/';

    $packageRepos = \preg_replace('/^(.+)$/', $splitRepositoryBase.'\1.git', $packages);
    $directoriesToRepositories = array_combine($packagesDirs, $packageRepos);

    $parameters->set(Option::DIRECTORIES_TO_REPOSITORIES, $directoriesToRepositories);
};
