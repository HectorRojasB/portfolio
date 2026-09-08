<section class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
    <flux:heading size="lg">{{ __('portfolio.experience.heading') }}</flux:heading>

    @if ($experiences->isEmpty())
        <flux:text class="mt-4">{{ __('portfolio.experience.empty') }}</flux:text>
    @else
        <div class="mt-8 space-y-8 border-s border-zinc-200 dark:border-zinc-800">
            @foreach ($experiences as $experience)
                <div class="relative ps-8">
                    <span class="absolute top-1.5 -start-[5px] size-2.5 rounded-full bg-zinc-400 dark:bg-zinc-600"></span>

                    <div class="flex flex-wrap items-center gap-2">
                        <flux:heading size="md">{{ $experience->trans('position') }}</flux:heading>
                        @if ($experience->is_current)
                            <flux:badge color="lime" size="sm">{{ __('portfolio.experience.current') }}</flux:badge>
                        @endif
                    </div>

                    <div class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">
                            @if ($experience->company->website_url)
                                <a href="{{ $experience->company->website_url }}" target="_blank" class="hover:underline">{{ $experience->company->name }}</a>
                            @else
                                {{ $experience->company->name }}
                            @endif
                        </span>
                        <span>&middot;</span>
                        <span>{{ $experience->employmentTypeLabel() }}</span>
                        <span>&middot;</span>
                        <span>{{ $experience->period() }}</span>
                        @if ($experience->location || $experience->is_remote)
                            <span>&middot;</span>
                            <span>{{ $experience->is_remote ? __('portfolio.experience.remote') : $experience->location }}</span>
                        @endif
                    </div>

                    @if ($experience->trans('description'))
                        <flux:text class="mt-3 whitespace-pre-line">{{ $experience->trans('description') }}</flux:text>
                    @endif

                    @if ($experience->technologies->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($experience->technologies as $technology)
                                <flux:badge size="sm">{{ $technology->name }}</flux:badge>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</section>
