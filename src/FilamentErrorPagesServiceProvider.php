<?php

namespace Cmsmaxinc\FilamentErrorPages;

use Cmsmaxinc\FilamentErrorPages\Support\TenantRouteParameterResolver;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

use function filament;

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
        $this->registerCustomErrorHandler();
    }

    protected function registerCustomErrorHandler(): void
    {
        app(ExceptionHandler::class)
            ->renderable(function (Throwable $exception, Request $request) {
                if (! method_exists($exception, 'getStatusCode')) {
                    return null;
                }

                // Get the status code of the exception
                $statusCode = $exception->getStatusCode();

                // Currently, we're only handling 403 and 404 status codes
                if (! in_array($statusCode, [403, 404])) {
                    return null;
                }

                // First try to find panel from configured routes
                $panelName = $this->getPanelFromPath($request->path());

                // If no panel found from routes and not restricted to configured routes, fall back to path-based detection
                if (! $panelName && ! $this->shouldOnlyShowForConfiguredRoutes()) {
                    $panelName = filament()->getCurrentPanel()?->getId()
                        ?? str($request->path())->before('/')->value();
                }

                // Set the current panel if it exists in the available panels
                if ($panel = filament()->getPanels()[$panelName] ?? false) {
                    filament()->setCurrentPanel($panel);

                    // Get the plugins of the current panel
                    $plugins = filament()->getCurrentPanel()->getPlugins();

                    // Check if the FilamentErrorPagesPlugin is used by the current panel
                    $usedByPanel = collect($plugins)->first(fn ($plugin) => $plugin instanceof FilamentErrorPagesPlugin);

                    if ($usedByPanel) {
                        $route = 'filament.' . $panel->getId() . '.pages.' . $statusCode;
                        $parameters = [];

                        if ($panel->hasTenancy()) {
                            $tenant = app(TenantRouteParameterResolver::class)->resolve($request, $panel);

                            if ($tenant === null) {
                                return null;
                            }

                            $parameters['tenant'] = $tenant;
                        }

                        $errorPageUrl = route($route, $parameters);

                        // Check if the previous request was redirected to the error page
                        $isRedirected = $request->url() === $errorPageUrl;

                        // Redirect to the panel error page unless the request is already there.
                        if (! $isRedirected) {
                            // https://github.com/livewire/livewire/discussions/4905#discussioncomment-7115155
                            return (new Redirector(App::get('url')))->to($errorPageUrl);
                        }
                    }
                }

                return null;
            });
    }

    protected function shouldOnlyShowForConfiguredRoutes(): bool
    {
        foreach (filament()->getPanels() as $panel) {
            $plugins = $panel->getPlugins();
            $plugin = collect($plugins)->first(fn ($plugin) => $plugin instanceof FilamentErrorPagesPlugin);

            if ($plugin && $plugin->shouldOnlyShowForConfiguredRoutes()) {
                return true;
            }
        }

        return false;
    }

    protected function getPanelFromPath(string $path): ?string
    {
        foreach (filament()->getPanels() as $panel) {
            $plugins = $panel->getPlugins();
            $plugin = collect($plugins)->first(fn ($plugin) => $plugin instanceof FilamentErrorPagesPlugin);

            if ($plugin) {
                $routes = $plugin->getRoutes();

                foreach ($routes as $pattern) {
                    if (Str::is($pattern, $path)) {
                        return $panel->getId();
                    }
                }
            }
        }

        return null;
    }

    protected function getAssetPackageName(): ?string
    {
        return 'cmsmaxinc/filament-error-pages';
    }
}
