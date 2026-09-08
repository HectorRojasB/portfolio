<section class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
    <flux:heading size="lg">{{ __('portfolio.interests.heading') }}</flux:heading>

    @if ($interests->isEmpty())
        <flux:text class="mt-4">{{ __('portfolio.interests.empty') }}</flux:text>
    @else
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            @foreach ($interests as $interest)
                <div class="flex items-start gap-3 rounded-xl border border-zinc-200 p-4 dark:border-zinc-800">
                    <flux:icon :name="$interest->iconName()" class="mt-0.5 size-5 shrink-0 text-zinc-500 dark:text-zinc-400" />

                    <div>
                        <flux:heading size="sm">{{ $interest->trans('name') }}</flux:heading>

                        @if ($interest->trans('description'))
                            <flux:text class="mt-1 whitespace-pre-line">{{ $interest->trans('description') }}</flux:text>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
