<?php

namespace Cmsmaxinc\FilamentErrorPages;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentErrorPagesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-error-pages';

    public static string $viewNamespace = 'filament-error-pages';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('cmsmaxinc/filament-error-pages');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'cmsmaxinc/filament-error-pages';
    }

    protected function getAssets(): array
    {
        return [
            //
        ];
    }

    protected function getCommands(): array
    {
        return [
            //
        ];
    }

    protected function getIcons(): array
    {
        return [
            //
        ];
    }

    protected function getRoutes(): array
    {
        return [
            //
        ];
    }

    protected function getScriptData(): array
    {
        return [
            //
        ];
    }

    protected function getMigrations(): array
    {
        return [
            //
        ];
    }
}
