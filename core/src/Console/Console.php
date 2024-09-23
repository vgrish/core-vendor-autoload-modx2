<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.0
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

namespace Vgrish\CoreVendorAutoloadMODX2\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\ListCommand;
use Vgrish\CoreVendorAutoloadMODX2\App;
use Vgrish\CoreVendorAutoloadMODX2\Console\Command\InstallCommand;
use Vgrish\CoreVendorAutoloadMODX2\Console\Command\RemoveCommand;

class Console extends Application
{
    public function __construct()
    {
        parent::__construct(App::NAMESPACE);
    }

    protected function getDefaultCommands(): array
    {
        return [
            new ListCommand(),
            new InstallCommand(),
            new RemoveCommand(),
        ];
    }
}
