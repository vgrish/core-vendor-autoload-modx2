<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.0
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

namespace Vgrish\CoreVendorAutoloadMODX2\Console\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    public const SUCCESS = SymfonyCommand::SUCCESS;
    public const FAILURE = SymfonyCommand::FAILURE;
    public const INVALID = SymfonyCommand::INVALID;
    protected static ?\modX $modx;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        self::$modx = \modX::getInstance(\modX::class);
    }

    final public static function modx(): \modX
    {
        return self::$modx;
    }
}
