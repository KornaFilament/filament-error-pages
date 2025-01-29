<?php

namespace Cmsmaxinc\FilamentErrorPages\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ForbiddenPage extends Page
{
    public function getCode(): string
    {
        return '403';
    }

    public function getTitle(): string | Htmlable
    {
        return __('The page you\'re looking for cannot be accessed.');
    }

    public function getDescription(): string
    {
        return __('You do not have permission to access the page you\'re looking for.');
    }

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = '403';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-error-pages::error-page';
}
