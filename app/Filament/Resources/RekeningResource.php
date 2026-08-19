<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekeningResource\Pages;
use App\Models\Rekening;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RekeningResource extends Resource
{
    protected static ?string $model = Rekening::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Rekening';

    protected static ?string $recordTitleAttribute = 'nama_bank';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_bank')
                ->label('Nama Bank')
                ->required()
                ->maxLength(50)
                ->placeholder('Bank Jateng'),

            TextInput::make('no_rekening')
                ->label('Nomor Rekening')
                ->required()
                ->maxLength(50),

            TextInput::make('atas_nama')
                ->label('Atas Nama')
                ->required()
                ->maxLength(150),

            Toggle::make('is_active')
                ->label('Aktif Ditampilkan')
                ->default(true)
                ->helperText('Rekening nonaktif tidak muncul sebagai opsi transfer di halaman Konfirmasi Transfer.'),

            TextInput::make('urutan')
                ->numeric()
                ->default(0)
                ->helperText('Urutan tampil di halaman depan, angka kecil di atas.'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bank')
                    ->label('Bank')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('no_rekening')
                    ->label('No. Rekening')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('atas_nama')
                    ->searchable(),
                TextColumn::make('pembayaran_count')
                    ->label('Jumlah Transaksi')
                    ->counts('pembayaran')
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('urutan')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('urutan')
            ->defaultSort('urutan');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekenings::route('/'),
            'create' => Pages\CreateRekening::route('/create'),
            'edit' => Pages\EditRekening::route('/{record}/edit'),
        ];
    }
}