<section class="w-full">
    <flux:heading size="xl">Perfil del portafolio</flux:heading>
    <flux:subheading>Esta es la información general que ven los reclutadores en tu portafolio.</flux:subheading>

    <form wire:submit="save" class="mt-6 max-w-2xl space-y-6">
        <flux:input wire:model="full_name" label="Nombre completo" required autofocus />

        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="headline_es" label="Titular (Español)" placeholder="Ej. Desarrollador Full Stack" />
            <flux:input wire:model="headline_en" label="Headline (English)" placeholder="E.g. Full Stack Developer" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:textarea wire:model="bio_es" label="Biografía (Español)" rows="4" />
            <flux:textarea wire:model="bio_en" label="Bio (English)" rows="4" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="email" label="Correo de contacto" type="email" />
            <flux:input wire:model="phone" label="Teléfono" />
        </div>

        <flux:input wire:model="location" label="Ubicación" />

        <div>
            @if ($avatar)
                <img src="{{ $avatar->temporaryUrl() }}" class="mb-2 size-20 rounded-full object-cover">
            @elseif ($existingAvatarUrl)
                <img src="{{ $existingAvatarUrl }}" class="mb-2 size-20 rounded-full object-cover">
            @endif
            <flux:input type="file" wire:model="avatar" label="Foto de perfil" accept="image/*" />
        </div>

        <div>
            @if ($existingResumeUrl && ! $resume)
                <flux:link href="{{ $existingResumeUrl }}" target="_blank" class="mb-2 block text-sm">Ver CV actual</flux:link>
            @endif
            <flux:input type="file" wire:model="resume" label="Currículum (PDF)" accept="application/pdf" />
        </div>

        <flux:separator />

        <flux:input wire:model="github_url" label="GitHub" type="url" placeholder="https://" />
        <flux:input wire:model="linkedin_url" label="LinkedIn" type="url" placeholder="https://" />
        <flux:input wire:model="twitter_url" label="Twitter / X" type="url" placeholder="https://" />
        <flux:input wire:model="website_url" label="Sitio web" type="url" placeholder="https://" />

        <flux:switch wire:model="available_for_hire" label="Disponible para trabajar" />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Guardar</flux:button>
        </div>
    </form>
</section>
