<div class="flex items-center justify-center min-h-[80vh]">
    <div class="text-center">
        <h1 class="text-9xl font-black text-center mb-4 relative">
            <span class="absolute text-gray-400 transform -rotate-12 -top-8 -left-6 opacity-50">
                404
            </span>
            <span class="relative z-10">404</span>
        </h1>

        <p class="text-xl font-semibold mb-2">
            The page you're looking for cannot be found.
        </p>

        <p class="text-xs text-gray-900 mb-6">
            The page you're looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>

        <div class="flex justify-center gap-4">

            @if(url()->previous() != url()->current())
                <x-filament::button icon="heroicon-o-arrow-uturn-left" tag="a" color="gray" :href="url()->previous()">
                    Go back to previous page
                </x-filament::button>
            @endif

            <x-filament::button icon="heroicon-s-home" tag="a" color="primary" :href="\Filament\Facades\Filament::getCurrentPanel()->getUrl()">
                Go back to home
            </x-filament::button>
        </div>
    </div>
</div>