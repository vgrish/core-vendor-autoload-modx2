<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Vgrish <vgrish@gmail.com>
 * "vgrish/core-vendor-autoload-modx2" package for CoreVendorAutoloadMODX2
 * The version 1.0.0
 * @see https://github.com/vgrish/core-vendor-autoload-modx2
 */

namespace Vgrish\CoreVendorAutoloadMODX2;

class App
{
    public const AUTHOR = 'vgrish';
    public const NAME = 'CoreVendorAutoloadMODX2';
    public const NAMESPACE = 'core-vendor-autoload-modx2';
    public const VERSION = '1.0.1';
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
}
