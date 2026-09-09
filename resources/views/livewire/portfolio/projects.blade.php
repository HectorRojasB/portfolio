<section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
    <flux:heading size="lg">{{ __('portfolio.projects.heading') }}</flux:heading>

    @if ($this->technologies->isNotEmpty())
        <div class="mt-6 flex flex-wrap gap-2">
            <flux:button
                size="sm"
                :variant="$technology === null ? 'primary' : 'filled'"
                wire:click="filterBy(null)"
            >
                {{ __('portfolio.projects.all') }}
            </flux:button>

            @foreach ($this->technologies as $tech)
                <flux:button
                    size="sm"
                    :variant="$technology === $tech->id ? 'primary' : 'filled'"
                    wire:click="filterBy({{ $tech->id }})"
                >
                    {{ $tech->name }}
                </flux:button>
            @endforeach
        </div>
    @endif

    @if ($this->projects->isEmpty())
        <flux:text class="mt-8">{{ __('portfolio.projects.empty') }}</flux:text>
    @else
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->projects as $project)
                <div class="group flex flex-col overflow-hidden rounded-xl border border-zinc-200 transition-colors hover:border-zinc-300 dark:border-zinc-800 dark:hover:border-zinc-700">
                    <button type="button" wire:click="viewProject({{ $project->id }})" class="flex flex-1 cursor-pointer flex-col text-left">
                        @if ($project->thumbnailUrl())
                            <img src="{{ $project->thumbnailUrl() }}" alt="{{ $project->title }}" class="aspect-video w-full object-cover">
                        @else
                            <div class="flex aspect-video w-full items-center justify-center bg-zinc-100 dark:bg-zinc-900">
                                <flux:icon name="photo" class="size-8 text-zinc-400" />
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-4">
                            <div class="flex items-center gap-2">
                                <flux:heading size="md" class="flex-1 group-hover:underline">{{ $project->title }}</flux:heading>
                                @if ($project->is_featured)
                                    <flux:icon name="star" variant="solid" class="size-4 text-amber-400" />
                                @endif
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                                <flux:badge size="sm">{{ $project->statusLabel() }}</flux:badge>
                                <span>{{ $project->contextLabel() }}</span>
                            </div>

                            @if ($project->trans('excerpt'))
                                <flux:text class="mt-2 flex-1 whitespace-pre-line">{{ $project->trans('excerpt') }}</flux:text>
                            @endif

                            @if ($project->technologies->isNotEmpty())
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($project->technologies as $technology)
                                        <flux:badge size="sm" variant="pill">{{ $technology->name }}</flux:badge>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </button>

                    <div class="flex gap-2 px-4 pb-4">
                        @if ($project->url)
                            <flux:button href="{{ $project->url }}" target="_blank" size="sm" variant="primary" class="flex-1">
                                {{ __('portfolio.projects.demo') }}
                            </flux:button>
                        @endif

                        @if ($project->repository_url)
                            <flux:button href="{{ $project->repository_url }}" target="_blank" size="sm" icon="code-bracket" class="flex-1">
                                {{ __('portfolio.projects.repository') }}
                            </flux:button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <flux:modal name="project-details" class="md:w-[36rem]">
        @if ($this->selectedProjectModel)
            @php($p = $this->selectedProjectModel)
            <div class="space-y-4">
                @if ($p->images->isNotEmpty())
                    <div class="relative">
                        <img src="{{ $p->images[$galleryIndex]->url() }}" alt="{{ $p->title }}" class="aspect-video w-full rounded-lg object-cover">

                        @if ($p->images->count() > 1)
                            <button
                                type="button"
                                wire:click="previousGalleryImage"
                                class="absolute top-1/2 left-2 flex size-8 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white hover:bg-black/70"
                            >
                                <flux:icon name="chevron-left" class="size-5" />
                            </button>
                            <button
                                type="button"
                                wire:click="nextGalleryImage"
                                class="absolute top-1/2 right-2 flex size-8 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white hover:bg-black/70"
                            >
                                <flux:icon name="chevron-right" class="size-5" />
                            </button>

                            <div class="absolute bottom-2 left-1/2 flex -translate-x-1/2 gap-1.5">
                                @foreach ($p->images as $index => $image)
                                    <button
                                        type="button"
                                        wire:click="setGalleryImage({{ $index }})"
                                        wire:key="dot-{{ $image->id }}"
                                        class="size-1.5 rounded-full {{ $index === $galleryIndex ? 'bg-white' : 'bg-white/40' }}"
                                    ></button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($p->images->count() > 1)
                        <div class="flex gap-2 overflow-x-auto">
                            @foreach ($p->images as $index => $image)
                                <button type="button" wire:click="setGalleryImage({{ $index }})" wire:key="thumb-{{ $image->id }}" class="shrink-0">
                                    <img
                                        src="{{ $image->url() }}"
                                        class="size-14 rounded-md object-cover {{ $index === $galleryIndex ? 'ring-2 ring-accent' : 'opacity-60' }}"
                                    >
                                </button>
                            @endforeach
                        </div>
                    @endif
                @elseif ($p->thumbnailUrl())
                    <img src="{{ $p->thumbnailUrl() }}" alt="{{ $p->title }}" class="aspect-video w-full rounded-lg object-cover">
                @endif

                <div class="flex items-start justify-between gap-2">
                    <flux:heading size="lg">{{ $p->title }}</flux:heading>
                    @if ($p->is_featured)
                        <flux:icon name="star" variant="solid" class="mt-1 size-5 shrink-0 text-amber-400" />
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                    <flux:badge size="sm">{{ $p->statusLabel() }}</flux:badge>
                    <span>{{ $p->contextLabel() }}</span>
                    @if ($p->period())
                        <span>&middot; {{ $p->period() }}</span>
                    @endif
                </div>

                @if ($p->trans('description') || $p->trans('excerpt'))
                    <flux:text class="whitespace-pre-line">{{ $p->trans('description') ?: $p->trans('excerpt') }}</flux:text>
                @endif

                @if ($p->technologies->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($p->technologies as $technology)
                            <flux:badge size="sm" variant="pill">{{ $technology->name }}</flux:badge>
                        @endforeach
                    </div>
                @endif

                <div class="flex gap-2 pt-2">
                    @if ($p->url)
                        <flux:button href="{{ $p->url }}" target="_blank" variant="primary" class="flex-1">
                            {{ __('portfolio.projects.demo') }}
                        </flux:button>
                    @endif

                    @if ($p->repository_url)
                        <flux:button href="{{ $p->repository_url }}" target="_blank" icon="code-bracket" class="flex-1">
                            {{ __('portfolio.projects.repository') }}
                        </flux:button>
                    @endif

                    <flux:modal.close>
                        <flux:button>{{ __('portfolio.common.close') }}</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</section>
