<?php

namespace Cmsmaxinc\FilamentErrorPages\Tests\Fixtures;

use Cmsmaxinc\FilamentErrorPages\FilamentErrorPagesPlugin;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Facades\Route;

class DomainTenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('domain-panel')
            ->path('workspace')
            ->tenant(Tenant::class, slugAttribute: 'slug')
            ->tenantDomain('{tenant:slug}.example.test')
            ->authenticatedTenantRoutes(function (): void {
                Route::get('broken', fn () => abort(404));
            })
            ->plugin(FilamentErrorPagesPlugin::make());
    }
}
