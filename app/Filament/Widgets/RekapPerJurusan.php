<?php

namespace App\Filament\Widgets;

use App\Models\Jurusan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RekapPerJurusan extends BaseWidget
{
    protected static ?string $heading = 'Rekap Pendaftar per Jurusan';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Jurusan::query()
                    ->where('is_active', true)
                    ->withCount([
                        'pendaftar as total_pendaftar',
                        'pendaftar as laki_laki' => fn ($query) => $query->where('jenis_kelamin', 'laki-laki'),
                        'pendaftar as perempuan' => fn ($query) => $query->where('jenis_kelamin', 'perempuan'),
                        'pendaftar as daftar_ulang' => fn ($query) => $query->whereNotNull('daftar_ulang_at'),
                    ])
                    ->orderByDesc('total_pendaftar')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Jurusan')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('total_pendaftar')
                    ->label('Total')
                    ->alignCenter()
                    ->badge()
                    ->color('primary')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Keseluruhan')),

                Tables\Columns\TextColumn::make('laki_laki')
                    ->label('Laki-laki')
                    ->alignCenter()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total')),

                Tables\Columns\TextColumn::make('perempuan')
                    ->label('Perempuan')
                    ->alignCenter()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total')),

                Tables\Columns\TextColumn::make('daftar_ulang')
                    ->label('Daftar Ulang')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total')),
            ])
            ->paginated(false);
    }
}