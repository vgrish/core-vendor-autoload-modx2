<?php

declare(strict_types=1);

use Ergebnis\PhpCsFixer\Config;

$header = <<<EOF
Copyright (c) 2024 Vgrish <vgrish@gmail.com>
"vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
The version 1.0.0
@see https://github.com/vgrish/core-vendor-autoload-modx2
EOF;

$ruleSet = Config\RuleSet\Php74::create()
    ->withHeader($header)
    ->withRules(
        Config\Rules::fromArray([
            'final_class' => false,
            'final_public_method_for_abstract_class' => false,
            'phpdoc_list_type' => false,
        ])
    );
$config = Config\Factory::fromRuleSet($ruleSet);

$config->getFinder()
    ->exclude('test')
    ->exclude('tmp')
    ->in(__DIR__);
return $config;