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

class RemoveCommand extends Command
{
    protected static $defaultName = 'remove';
    protected static $defaultDescription = 'Remove "' . App::NAMESPACE . '" extra from MODX 2';

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $srcPath = MODX_CORE_PATH . 'vendor/' . App::AUTHOR . '/' . App::NAMESPACE;
        $corePath = MODX_CORE_PATH . 'components/' . App::NAMESPACE;

        if (\is_dir($corePath)) {
            \unlink($corePath);
            $output->writeln('<info>Removed symlink for "core"</info>');
        }

        $modx = self::modx();

        if ($namespace = $modx->getObject(\modNamespace::class, ['name' => App::NAME])) {
            $namespace->remove();
            $output->writeln('<info>Removed namespace "' . App::NAME . '"</info>');
        }

        $modx->getCacheManager()->refresh();
        $output->writeln('<info>Cleared MODX cache</info>');

        return Command::SUCCESS;
    }
}
