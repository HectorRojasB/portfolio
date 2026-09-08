<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <flux:header container class="border-b border-zinc-200 dark:border-zinc-800">
            <a href="{{ route('home', app()->getLocale()) }}" class="flex items-center gap-2 font-medium" wire:navigate>
                <x-app-logo-icon class="size-6 fill-current" />
                <span>{{ config('app.name') }}</span>
            </a>

            <flux:spacer />

            <flux:navbar class="max-lg:hidden">
                <flux:navbar.item href="#experiencia">{{ __('portfolio.nav.experience') }}</flux:navbar.item>
                <flux:navbar.item href="#proyectos">{{ __('portfolio.nav.projects') }}</flux:navbar.item>
                <flux:navbar.item href="#intereses">{{ __('portfolio.nav.interests') }}</flux:navbar.item>
            </flux:navbar>

            <flux:dropdown position="bottom" align="end">
                <flux:button size="sm" icon="language">{{ strtoupper(app()->getLocale()) }}</flux:button>

                <flux:menu>
                    @foreach (config('portfolio.locales') as $locale)
                        <flux:menu.item :href="route('home', $locale)" :current="app()->getLocale() === $locale" wire:navigate>
                            {{ strtoupper($locale) }}
                        </flux:menu.item>
                    @endforeach
                </flux:menu>
            </flux:dropdown>

            @auth
                <flux:button href="{{ route('dashboard') }}" size="sm" wire:navigate>{{ __('portfolio.nav.panel') }}</flux:button>
            @endauth
        </flux:header>

        <main>
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200 py-8 text-center text-sm text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
            &copy; {{ now()->year }} {{ config('app.name') }}. {{ __('portfolio.footer.rights') }}
        </footer>

        @fluxScripts
    </body>
</html>
