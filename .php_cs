<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PSR2' => true
];

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->setFinder($finder)
    ->setUsingCache(false);