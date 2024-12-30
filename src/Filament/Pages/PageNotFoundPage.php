<?php

namespace Cmsmaxinc\FilamentErrorPages\Filament\Pages;

use Filament\Pages\Page;

class PageNotFoundPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'woops';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-error-pages::404';
}
