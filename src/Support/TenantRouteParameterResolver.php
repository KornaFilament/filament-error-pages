<?php

namespace Cmsmaxinc\FilamentErrorPages\Support;

use Filament\Panel;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

final class TenantRouteParameterResolver
{
    public function resolve(Request $request, Panel $panel): ?string
    {
        if (! $panel->hasTenancy()) {
            return null;
        }

        $routeTenant = $this->normalize($request->route('tenant'), $panel);

        if ($routeTenant !== null) {
            return $routeTenant;
        }

        if ($panel->hasTenantDomain()) {
            return $this->resolveFromDomain($request, $panel);
        }

        return $this->resolveFromPath($request, $panel);
    }

    private function resolveFromDomain(Request $request, Panel $panel): ?string
    {
        $route = (new Route(['GET'], '{filamentErrorPagesPath?}', fn (): null => null))
            ->domain($panel->getTenantDomain())
            ->where('filamentErrorPagesPath', '.*');

        if (Str::is(['{tenant}', '{tenant:*}'], $panel->getTenantDomain())) {
            $route->where('tenant', '[a-z0-9.\-]+');
        }

        $route->bind($request);

        return $this->normalize($route->parameter('tenant'));
    }

    private function resolveFromPath(Request $request, Panel $panel): ?string
    {
        $uri = collect([
            trim($panel->getPath(), '/'),
            trim((string) $panel->getTenantRoutePrefix(), '/'),
            '{tenant}',
            '{filamentErrorPagesPath?}',
        ])
            ->filter(fn (string $segment): bool => $segment !== '')
            ->implode('/');

        $route = (new Route(['GET'], $uri, fn (): null => null))
            ->where('filamentErrorPagesPath', '.*')
            ->bind($request);

        return $this->normalize($route->parameter('tenant'));
    }

    private function normalize(mixed $tenant, ?Panel $panel = null): ?string
    {
        if (
            ($tenant instanceof Model) &&
            filled($tenantSlugAttribute = $panel?->getTenantSlugAttribute())
        ) {
            $tenant = $tenant->getAttributeValue($tenantSlugAttribute);
        } elseif ($tenant instanceof UrlRoutable) {
            $tenant = $tenant->getRouteKey();
        }

        if (! is_string($tenant) && ! is_int($tenant)) {
            return null;
        }

        $tenant = trim((string) $tenant);

        return $tenant === '' ? null : $tenant;
    }
}
