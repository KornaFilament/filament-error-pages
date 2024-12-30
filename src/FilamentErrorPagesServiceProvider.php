<?php

namespace Cmsmaxinc\FilamentErrorPages;

use Filament\Facades\Filament;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        // TODO: Parse the URL (https://leadpulse.test/company/52/woops) grab the firt part /company/52 and append the /woops to it
        $this->registerCustomErrorHandler();
    }

    protected function registerCustomErrorHandler(): void
    {
        app(ExceptionHandler::class)
            ->renderable(function (Throwable $e, $request) {
                /*
                 * TODO: This is a temporary solution, we need to find a better way to handle this
                 * But we are getting null from filament()->getCurrentPanel()
                 * So we are using the request path to get the panel name
                 */

                $panelName = str(request()->path())->before('/')->value();
                $tenant = str(request()->path())->match('/\d+/')->value();
                $panel = filament()->getPanel($panelName);

                if (array_key_exists($panelName, filament()->getPanels())) {
                    filament()->setCurrentPanel($panel);
                }

                if ($e instanceof NotFoundHttpException) {
                    if (array_key_exists($panelName, filament()->getPanels())) {
                        return redirect()->route('filament.' . $panel->getId() . '.pages.woops', filament()->getCurrentPanel()->getTenantModel() ? $tenant : null);
                    }
                }

                return null;
            });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'cmsmaxinc/filament-error-pages';
    }
}
