<?php

use Cmsmaxinc\FilamentErrorPages\Tests\Fixtures\User;

it('redirects path-based slug tenants to their error page', function () {
    $this->actingAs(new User)
        ->get('/admin/acme-co/broken')
        ->assertRedirect('/admin/acme-co/404');
});

it('redirects subdomain tenants to their error page', function () {
    $this->actingAs(new User)
        ->get('http://acme-co.example.test/workspace/broken')
        ->assertRedirect('http://acme-co.example.test/workspace/404');
});
