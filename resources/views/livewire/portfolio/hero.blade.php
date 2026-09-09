<section class="mx-auto max-w-3xl px-6 pt-16 pb-4 text-center lg:px-8 lg:pt-24">
    @if ($profile)
        @if ($profile->avatarUrl())
            <img
                src="{{ $profile->avatarUrl() }}"
                alt="{{ $profile->full_name }}"
                class="mx-auto size-28 rounded-full object-cover ring-4 ring-zinc-100 dark:ring-zinc-800"
            >
        @endif

        <flux:heading size="xl" class="mt-6 !text-3xl lg:!text-4xl">
            {{ $profile->full_name }}
        </flux:heading>

        @if ($profile->trans('headline'))
            <flux:text class="mt-2 text-lg">{{ $profile->trans('headline') }}</flux:text>
        @endif

        <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
            @if ($profile->location)
                <span class="inline-flex items-center gap-1 rounded-full border border-zinc-200 px-3 py-1 text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                    <flux:icon name="map-pin" class="size-4" />
                    {{ $profile->location }}
                </span>
            @endif

            @if ($yearsOfExperience)
                <span class="inline-flex items-center gap-1 rounded-full border border-zinc-200 px-3 py-1 text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                    <flux:icon name="briefcase" class="size-4" />
                    {{ $yearsOfExperience }}+ {{ __('portfolio.hero.years_experience') }}
                </span>
            @endif

            @if ($profile->available_for_hire)
                <flux:badge color="lime" size="sm">{{ __('portfolio.hero.available') }}</flux:badge>
            @endif
        </div>

        @if ($topTechnologies->isNotEmpty())
            <div class="mt-3 flex flex-wrap items-center justify-center gap-1.5">
                @foreach ($topTechnologies as $technology)
                    <flux:badge size="sm" variant="pill">{{ $technology->name }}</flux:badge>
                @endforeach
            </div>
        @endif

        @if ($profile->trans('bio'))
            <flux:text class="mx-auto mt-6 max-w-xl text-justify whitespace-pre-line">{{ $profile->trans('bio') }}</flux:text>
        @endif

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            @if ($profile->email)
                <flux:button href="mailto:{{ $profile->email }}" variant="primary" icon="envelope">
                    {{ __('portfolio.hero.contact') }}
                </flux:button>
            @endif

            @if ($profile->resumeUrl())
                <flux:button href="{{ $profile->resumeUrl() }}" target="_blank" icon="arrow-down-tray">
                    {{ __('portfolio.hero.download_cv') }}
                </flux:button>
            @endif

            @if ($profile->github_url)
                <flux:button href="{{ $profile->github_url }}" target="_blank" variant="ghost" icon="code-bracket">
                    GitHub
                </flux:button>
            @endif

            @if ($profile->linkedin_url)
                <flux:button href="{{ $profile->linkedin_url }}" target="_blank" variant="ghost" icon="briefcase">
                    LinkedIn
                </flux:button>
            @endif

            @if ($profile->website_url)
                <flux:button href="{{ $profile->website_url }}" target="_blank" variant="ghost" icon="globe-alt">
                    {{ __('portfolio.hero.website') }}
                </flux:button>
            @endif
        </div>
    @else
        <flux:heading size="xl">{{ __('portfolio.hero.welcome') }}</flux:heading>
        <flux:text class="mt-2">{{ __('portfolio.hero.no_profile') }}</flux:text>
    @endif
</section>
