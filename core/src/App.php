<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.2
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

namespace Vgrish\CoreVendorAutoloadMODX2;

class App
{
    public const AUTHOR = 'vgrish';
    public const NAME = 'CoreVendorAutoloadMODX2';
    public const NAMESPACE = 'core-vendor-autoload-modx2';
    public const VERSION = '1.0.2';
    public static \modX $modx;
    protected static $instance;

    public function __construct(?\modX $modx = null)
    {
        if (null === $modx) {
            $modx = \modX::getInstance(\modX::class);
        }

        self::$modx = $modx;
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function modx(): \modX
    {
        return (self::getInstance())::$modx;
    }

    public static function dependencies(): array
    {
        $lockFile = MODX_BASE_PATH . 'composer.lock';

        if (!\is_readable($lockFile)) {
            return [];
        }

        $array = \json_decode(\file_get_contents($lockFile), true);
        $composerHash = $array['content-hash'] ?? '';

        $dependenceDir = MODX_CORE_PATH . 'components/' . self::NAMESPACE . '/dependencies/';
        $dependenceFile = $dependenceDir . $composerHash . '.php';

        if (\is_readable($dependenceFile)) {
            return (array) require $dependenceFile;
        }

        if (\is_dir($dependenceDir)) {
            foreach (\glob($dependenceDir . '/*') as $file) {
                if (\is_file($file)) {
                    \unlink($file);
                }
            }
        } else {
            if (!\mkdir($dependenceDir, 0755, true)) {
                return [];
            }
        }

        $packages = $array['packages'] ?? [];
        $packages = \array_reduce($packages, static fn ($result, $item) => $result + [$item['name'] => $item], []);

        $vendorPackages = \array_flip(\array_keys($packages));

        foreach ($packages as $name => $package) {
            $packages[$name] = \array_merge($package, [
                'relations' => \array_keys(\array_intersect_key($package['require'], $vendorPackages)),
            ]);
        }

        $packageSort = static function ($array) {
            $sorted = [];
            $entered = [];

            // DFS
            function has($item, &$array, &$sorted, &$entered): void
            {
                if (!\array_key_exists($item, $entered)) {
                    $entered[$item] = true;

                    if (isset($array[$item]['relations'])) {
                        foreach ($array[$item]['relations'] as $dependency) {
                            has($dependency, $array, $sorted, $entered);
                        }
                    }

                    $sorted[] = $array[$item];
                }
            }

            foreach ($array as $key => $value) {
                has($key, $array, $sorted, $entered);
            }

            return $sorted;
        };

        $dependencies = [];

        foreach ($packageSort($packages) as $package) {
            [, $name] = \explode('/', $package['name']);
            $dependencies[$name] = $package;
        }

        \file_put_contents($dependenceFile, "<?php\n\nreturn " . \var_export($dependencies, true) . ";\n");

        return $dependencies;
    }
}
