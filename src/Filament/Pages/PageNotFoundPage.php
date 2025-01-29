<?php

namespace Cmsmaxinc\FilamentErrorPages\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class PageNotFoundPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = '404';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-error-pages::error-page';

    public function getCode(): string
    {
        return '404';
    }

    public function getTitle(): string | Htmlable
    {
        return __('The page you\'re looking for cannot be found.');
    }

    public function getDescription(): string | Htmlable
    {
        return __('The page you\'re looking for might have been removed, had its name changed, or is temporarily unavailable.');
    }
}
