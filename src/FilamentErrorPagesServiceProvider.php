<?php

namespace Cmsmaxinc\FilamentErrorPages;

use Filament\Facades\Filament;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
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
            ->renderable(function (Throwable $exception, $request) {
                /**
                 * Temporary solution to grab the panel name from the request path.
                 * The current panel is null "filament()->getCurrentPanel()", so we're deriving the panel name from the request path.
                 * A more robust solution is needed in the future.
                 */
                $path = str(request()->path());
                $panelName = $path->before('/')->value();
                $tenantId = $path->match('/\d+/')->value();
                $panel = filament()->getPanel($panelName);

                // Set the current panel if it exists in the available panels
                if (filament()->getPanels()[$panelName] ?? false) {
                    filament()->setCurrentPanel($panel);
                }

                // Check if the previous request was redirected to the woops page
                $isRedirected = request()->url() === route(
                    'filament.' . $panel->getId() . '.pages.woops',
                    filament()->getCurrentPanel()->getTenantModel() ? $tenantId : null
                );

                // Handle NotFoundHttpException for panels
                if ($exception instanceof NotFoundHttpException && ! $isRedirected) {
                    $isDefaultPanel = filament()->getCurrentPanel()->getId() === filament()->getDefaultPanel()->getId();

                    if (filament()->getPanels()[$panelName] ?? $isDefaultPanel) {
                        return (new Redirector(App::get('url')))->route(
                            'filament.' . $panel->getId() . '.pages.woops',
                            filament()->getCurrentPanel()->getTenantModel() ? $tenantId : null
                        );
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
