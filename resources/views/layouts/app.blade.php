<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light dark">

        <title>{{ $title ?? config('app.name', 'A.S Bakers') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-background text-foreground min-h-screen antialiased">
        <div class="min-h-screen">
            <header class="bg-background/95 supports-[backdrop-filter]:bg-background/80 sticky top-0 z-50 border-b backdrop-blur">
                <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
                    <a href="{{ route('tags.generator') }}" class="flex min-w-0 items-center gap-3" wire:navigate>
                        <span class="bg-primary text-primary-foreground flex size-9 shrink-0 items-center justify-center rounded-lg shadow-xs">
                            <x-ui.icon name="tags" class="size-5" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-semibold sm:text-base">A.S Bakers</span>
                            <span class="text-muted-foreground hidden text-xs sm:block">{{ __('Expiry Tag Generator') }}</span>
                        </span>
                    </a>

                    <div class="flex items-center gap-2">
                        @php
                            $otherLocale = app()->getLocale() === 'ur' ? 'en' : 'ur';
                        @endphp
                        <x-ui.button
                            href="{{ route('locale.switch', $otherLocale) }}"
                            variant="outline"
                            size="icon"
                            :aria-label="__('Switch language')"
                            :title="\App\Http\Middleware\SetLocale::SUPPORTED[$otherLocale]"
                        >
                            <x-ui.icon name="languages" />
                            <span class="sr-only">{{ __('Switch language') }}</span>
                        </x-ui.button>

                        <x-ui.button
                            variant="outline"
                            size="icon"
                            x-data
                            x-on:click="$store.theme.toggle()"
                            x-bind:aria-label="$store.theme.isDark ? {{ Illuminate\Support\Js::from(__('Use light mode')) }} : {{ Illuminate\Support\Js::from(__('Use dark mode')) }}"
                            x-bind:title="$store.theme.mode === 'system' ? {{ Illuminate\Support\Js::from(__('Using system theme')) }} : ($store.theme.isDark ? {{ Illuminate\Support\Js::from(__('Using dark theme')) }} : {{ Illuminate\Support\Js::from(__('Using light theme')) }})"
                        >
                            <x-ui.icon name="sun" x-show="$store.theme.isDark" x-cloak />
                            <x-ui.icon name="moon" x-show="!$store.theme.isDark" x-cloak />
                            <span class="sr-only">{{ __('Toggle color theme') }}</span>
                        </x-ui.button>
                    </div>
                </nav>
            </header>

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
