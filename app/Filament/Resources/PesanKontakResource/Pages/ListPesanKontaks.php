<?php

namespace App\Filament\Resources\PesanKontakResource\Pages;

use App\Filament\Resources\PesanKontakResource;
use Filament\Resources\Pages\ListRecords;

class ListPesanKontaks extends ListRecords
{
    protected static string $resource = PesanKontakResource::class;

    // Tidak ada tombol "Create" -- pesan hanya masuk dari form Kontak publik.
    protected function getHeaderActions(): array
    {
        return [];
    }
}