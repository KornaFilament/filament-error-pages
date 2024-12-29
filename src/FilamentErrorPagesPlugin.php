<?php

namespace Cmsmaxinc\FilamentErrorPages;

use Cmsmaxinc\FilamentErrorPages\Filament\Pages\ErrorPage;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentErrorPagesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-error-pages';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            ErrorPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
