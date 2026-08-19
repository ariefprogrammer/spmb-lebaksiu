<?php

namespace App\Filament\Resources\AlurPendaftaranResource\Pages;

use App\Filament\Resources\AlurPendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAlurPendaftaran extends ViewRecord
{
    protected static string $resource = AlurPendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
