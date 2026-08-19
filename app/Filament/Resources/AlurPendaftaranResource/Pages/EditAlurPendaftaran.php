<?php

namespace App\Filament\Resources\AlurPendaftaranResource\Pages;

use App\Filament\Resources\AlurPendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlurPendaftaran extends EditRecord
{
    protected static string $resource = AlurPendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
