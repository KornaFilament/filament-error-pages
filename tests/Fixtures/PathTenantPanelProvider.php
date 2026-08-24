<?php

namespace Cmsmaxinc\FilamentErrorPages\Tests\Fixtures;

use Cmsmaxinc\FilamentErrorPages\FilamentErrorPagesPlugin;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Facades\Route;

class PathTenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('control-panel')
            ->path('admin')
            ->tenant(Tenant::class, slugAttribute: 'slug')
            ->authenticatedTenantRoutes(function (): void {
                Route::get('broken', fn () => abort(404));
            })
            ->plugin(FilamentErrorPagesPlugin::make());
    }
}
