<?php

namespace App\Filament\Widgets;

use App\Models\Guru;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RekapPerGuru extends BaseWidget
{
    protected static ?string $heading = 'Rekap Pendaftar Bawaan per Guru';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Guru::query()
                    ->where('is_active', true)
                    ->withCount('rekomendasiPendaftar')
                    ->having('rekomendasi_pendaftar_count', '>', 0)
                    ->orderByDesc('rekomendasi_pendaftar_count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Guru')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('mapel')
                    ->label('Mapel'),

                Tables\Columns\TextColumn::make('jurusan.nama')
                    ->label('Jurusan')
                    ->placeholder('Umum'),

                Tables\Columns\TextColumn::make('rekomendasi_pendaftar_count')
                    ->label('Jumlah Pendaftar Bawaan')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Keseluruhan')),
            ])
            ->paginated([10, 25, 50]);
    }
}