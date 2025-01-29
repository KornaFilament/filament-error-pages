<?php

namespace Cmsmaxinc\FilamentErrorPages\Filament\Pages;

use Filament\Pages\Page;

class ForbiddenPage extends Page
{
    public string $code = '403';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = '403';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-error-pages::error-page';
}
