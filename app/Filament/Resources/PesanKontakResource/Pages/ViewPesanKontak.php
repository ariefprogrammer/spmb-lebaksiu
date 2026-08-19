<?php

namespace App\Filament\Resources\PesanKontakResource\Pages;

use App\Filament\Resources\PesanKontakResource;
use App\Models\PesanKontak;
use Filament\Resources\Pages\ViewRecord;

class ViewPesanKontak extends ViewRecord
{
    protected static string $resource = PesanKontakResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Otomatis tandai sudah dibaca begitu panitia membuka pesannya.
        /** @var PesanKontak $pesan */
        $pesan = $this->getRecord();

        if (! $pesan->is_read) {
            $pesan->update(['is_read' => true]);
        }
    }
}