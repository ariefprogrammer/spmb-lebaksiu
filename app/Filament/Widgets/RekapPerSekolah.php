<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftar;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RekapPerSekolah extends BaseWidget
{
    protected static ?string $heading = 'Rekap Pendaftar per Sekolah Asal (SMP)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftar::query()
                    ->selectRaw('MIN(id) as id, asal_sekolah, count(*) as total')
                    ->groupBy('asal_sekolah')
                    ->orderByDesc('total')
            )
            ->columns([
                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah (SMP)'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Jumlah Pendaftar')
                    ->alignCenter()
                    ->badge()
                    ->color('primary')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Keseluruhan')),
            ])
            ->paginated([10, 25, 50]);
    }
}