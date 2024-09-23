<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.0
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

namespace Vgrish\CoreVendorAutoloadMODX2\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vgrish\CoreVendorAutoloadMODX2\App;

class InstallCommand extends Command
{
    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Install "' . App::NAMESPACE . '" extra for MODX 2';

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $modx = self::modx();

        $srcPath = MODX_CORE_PATH . 'vendor/' . App::AUTHOR . '/' . App::NAMESPACE;
        $corePath = MODX_CORE_PATH . 'components/' . App::NAMESPACE;

        if (!\is_dir($corePath)) {
            \symlink($srcPath . '/core', $corePath);
            $output->writeln('<info>Created symlink for "core"</info>');
        }

        if (!$modx->getObject(\modNamespace::class, ['name' => App::NAME])) {
            $namespace = new \modNamespace($modx);
            $namespace->fromArray(
                [
                    'name' => App::NAME,
                    'path' => '{core_path}components/' . App::NAMESPACE . '/',
                    'assets_path' => '',
                ],
                false,
                true,
            );
            $namespace->save();
            $output->writeln('<info>Created namespace "' . App::NAME . '"</info>');
        }

        if (!$modx->getObject(\modExtensionPackage::class, ['name' => App::NAME])) {
            $extension = new \modExtensionPackage($modx);
            $extension->fromArray(
                [
                    'name' => App::NAME,
                    'namespace' => App::NAME,
                    'service_name' => App::NAME,
                    'service_class' => App::NAME,
                    'path' => MODX_CORE_PATH . 'components/' . App::NAMESPACE . '/',
                ],
                false,
                true,
            );
            $extension->save();
            $output->writeln('<info>Created extension package"' . App::NAME . '"</info>');
        }

        $modx->getCacheManager()->refresh();
        $output->writeln('<info>Cleared MODX cache</info>');

        return Command::SUCCESS;
    }
}
