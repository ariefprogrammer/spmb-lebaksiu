<x-filament-panels::page>
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            Filter Laporan
        </x-slot>

        <form wire:submit="submitFilter" class="space-y-4">
            {{ $this->form }}

            <div class="flex justify-end gap-x-3">
                <x-filament::button type="submit" icon="heroicon-m-funnel">
                    Tampilkan Data
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    {{ $this->table }}
</x-filament-panels::page>