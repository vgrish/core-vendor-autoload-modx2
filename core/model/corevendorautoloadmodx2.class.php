<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.2
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

require_once MODX_CORE_PATH . 'vendor/autoload.php';

use Vgrish\CoreVendorAutoloadMODX2\App;

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
        \Composer\InstalledVersions::getAllRawData();

        $modx = &self::$modx;

        $showStartupErrors = (bool) $modx->getOption(App::NAMESPACE . '.show_startup_errors', null);

        $triedToLoad = [];
        $dependencies = App::dependencies();

        foreach ($dependencies as $packageComponentsDirName => $package) {
            $bootstrapFile = MODX_CORE_PATH . 'components/' . $packageComponentsDirName . '/bootstrap.php';
            $triedToLoad[$bootstrapFile] = $bootstrapFile;

            if (\is_readable($bootstrapFile)) {
                try {
                    require $bootstrapFile;
                } catch (Error $e) {
                    if ($showStartupErrors) {
                        $modx->log(
                            \modX::LOG_LEVEL_ERROR,
                            \sprintf(
                                'include file `%s` failed with an error: `%s` line: `%s`',
                                $e->getFile(),
                                $e->getMessage(),
                                $e->getLine(),
                            ),
                        );
                    }
                }
            }
        }

        $namespaces = $modx->call(\modNamespace::class, 'loadCache', [&$modx]);

        foreach ($namespaces as $namespace) {
            $bootstrapFile = $namespace['path'] . 'bootstrap.php';

            if (!\array_key_exists($bootstrapFile, $triedToLoad) && \is_readable($bootstrapFile)) {
                try {
                    require $bootstrapFile;
                } catch (Error $e) {
                    if ($showStartupErrors) {
                        $modx->log(
                            \modX::LOG_LEVEL_ERROR,
                            \sprintf(
                                'include file `%s` failed with an error: `%s` line: `%s`',
                                $e->getFile(),
                                $e->getMessage(),
                                $e->getLine(),
                            ),
                        );
                    }
                }
            }
        }
    }
}
