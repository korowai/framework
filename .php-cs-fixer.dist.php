<?php declare(strict_types=1);

$header = <<<'EOF'
This file is part of Korowai framework.

(c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>

Distributed under MIT license.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/packages/*/src')
    ->in(__DIR__ . '/packages/*/tests')
    ->in(__DIR__ . '/packages/*/tests-nocov')
    ->in(__DIR__ . '/packages/*/testing')
    ->in(__DIR__ . '/packages/*/behat')
    ->in(__DIR__ . '/packages/*/config')
    ->name('*.php')
;

$config = new PhpCsFixer\Config();

$config
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => $header,
            'location' => 'after_open',
        ],
        'array_syntax' => ['syntax' => 'short'],
        'psr_autoloading' => true,
    ])
;

return $config;
// vim: syntax=php sw=4 ts=4 et: