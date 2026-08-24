<?php

use Cmsmaxinc\FilamentErrorPages\Support\TenantRouteParameterResolver;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class TenantRouteParameterResolverTestTenant extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

function tenantPanel(): Panel
{
    return Panel::make()
        ->id('admin')
        ->path('admin')
        ->tenant(TenantRouteParameterResolverTestTenant::class, slugAttribute: 'slug');
}

it('resolves numeric tenant route keys from the configured path position', function () {
    $request = Request::create('/admin/42/orders/123');

    expect(app(TenantRouteParameterResolver::class)->resolve($request, tenantPanel()))
        ->toBe('42');
});

it('resolves slug tenant route keys from the configured path position', function () {
    $request = Request::create('/admin/acme-co/missing-page');

    expect(app(TenantRouteParameterResolver::class)->resolve($request, tenantPanel()))
        ->toBe('acme-co');
});

it('respects custom tenant route prefixes', function () {
    $panel = tenantPanel()->tenantRoutePrefix('teams/for');
    $request = Request::create('/admin/teams/for/acme-co/missing-page');

    expect(app(TenantRouteParameterResolver::class)->resolve($request, $panel))
        ->toBe('acme-co');
});

it('resolves tenant route keys from subdomains', function () {
    $panel = tenantPanel()->tenantDomain('{tenant:slug}.example.test');
    $request = Request::create('https://acme-co.example.test/admin/missing-page');

    expect(app(TenantRouteParameterResolver::class)->resolve($request, $panel))
        ->toBe('acme-co');
});

it('uses an already-bound tenant before inspecting the URL', function () {
    $request = Request::create('/admin/path-tenant/known-page');
    $route = (new Route(['GET'], 'admin/{tenant}/known-page', fn (): null => null))
        ->bind($request);
    $request->setRouteResolver(fn (): Route => $route);

    expect(app(TenantRouteParameterResolver::class)->resolve($request, tenantPanel()))
        ->toBe('path-tenant');
});

it('uses the route key of an already-bound tenant model', function () {
    $tenant = new TenantRouteParameterResolverTestTenant;
    $tenant->slug = 'bound-tenant';

    $route = (new Route(['GET'], 'admin/known-page', fn (): null => null))
        ->bind(Request::create('/admin/known-page'));
    $route->setParameter('tenant', $tenant);

    $request = Request::create('/admin/known-page');
    $request->setRouteResolver(fn (): Route => $route);

    expect(app(TenantRouteParameterResolver::class)->resolve($request, tenantPanel()))
        ->toBe('bound-tenant');
});

it('does not guess a tenant when the configured path or domain does not match', function (Panel $panel, string $url) {
    expect(app(TenantRouteParameterResolver::class)->resolve(Request::create($url), $panel))
        ->toBeNull();
})->with([
    'different panel path' => [fn () => tenantPanel(), '/staff/acme-co/missing-page'],
    'different tenant domain' => [fn () => tenantPanel()->tenantDomain('{tenant:slug}.example.test'), 'https://example.test/admin/missing-page'],
]);

it('does not resolve a tenant for panels without tenancy', function () {
    $panel = Panel::make()->id('admin')->path('admin');

    expect(app(TenantRouteParameterResolver::class)->resolve(
        Request::create('/admin/acme-co/missing-page'),
        $panel,
    ))->toBeNull();
});
