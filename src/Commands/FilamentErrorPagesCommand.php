<?php

namespace Cmsmaxinc\FilamentErrorPages\Commands;

use Illuminate\Console\Command;

class FilamentErrorPagesCommand extends Command
{
    public $signature = 'filament-error-pages';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
