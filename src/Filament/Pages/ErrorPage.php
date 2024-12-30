<?php

namespace Cmsmaxinc\FilamentErrorPages\Filament\Pages;

use Filament\Pages\Page;

class ErrorPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-error-pages::404';
}
