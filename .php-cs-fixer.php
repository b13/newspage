<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setUsingCache(false)
    ->getFinder()
    ->in(__DIR__);

return $config;
