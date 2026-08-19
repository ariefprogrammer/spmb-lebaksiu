<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriKategoriResource\Pages;
use App\Models\GaleriKategori;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GaleriKategoriResource extends Resource
{
    protected static ?string $model = GaleriKategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Kategori Galeri';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->required()
                ->maxLength(60)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->required()
                ->maxLength(70)
                ->unique(ignoreRecord: true),

            TextInput::make('urutan')
                ->numeric()
                ->default(0)
                ->helperText('Urutan tampil di filter Galeri, angka kecil di atas.'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('galeri_count')
                    ->label('Jumlah Foto')
                    ->counts('galeri')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('urutan')
                    ->sortable(),
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
            'index' => Pages\ListGaleriKategoris::route('/'),
            'create' => Pages\CreateGaleriKategori::route('/create'),
            'edit' => Pages\EditGaleriKategori::route('/{record}/edit'),
        ];
    }
}