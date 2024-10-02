<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.0
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

require_once MODX_CORE_PATH . 'vendor/autoload.php';

class CoreVendorAutoloadMODX2
{
    public static \modX $modx;

    public function __construct(?\modX $modx = null)
    {
        if (null === $modx) {
            $modx = \modX::getInstance(\modX::class);
        }

        self::$modx = $modx;

        $this->initNamespaces();
    }

    protected function initNamespaces(): void
    {
        $modx = &self::$modx;
        $namespaces = $modx->call(\modNamespace::class, 'loadCache', [&$modx]);

        foreach ($namespaces as $namespace) {
            if (\is_readable($namespace['path'] . 'bootstrap.php')) {
                try {
                    require $namespace['path'] . 'bootstrap.php';
                } catch (Error $e) {
                    $modx->log(
                        \modX::LOG_LEVEL_ERROR,
                        \sprintf('include file `%s` failed with an error: `%s` line: `%s`', $e->getFile(), $e->getMessage(), $e->getLine()),
                    );
                }
            }
        }
    }
}
