<?php

namespace App\Filament\Resources\GaleriKategoriResource\Pages;

use App\Filament\Resources\GaleriKategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaleriKategoris extends ListRecords
{
    protected static string $resource = GaleriKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}