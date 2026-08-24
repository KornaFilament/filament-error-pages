<?php

namespace Cmsmaxinc\FilamentErrorPages\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $tenant = new static;
        $tenant->setAttribute($field ?? $this->getRouteKeyName(), $value);
        $tenant->exists = true;

        return $tenant;
    }
}
