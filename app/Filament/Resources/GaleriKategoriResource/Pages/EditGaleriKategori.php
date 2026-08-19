<?php

namespace App\Filament\Resources\GaleriKategoriResource\Pages;

use App\Filament\Resources\GaleriKategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGaleriKategori extends EditRecord
{
    protected static string $resource = GaleriKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}