<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriResource\Pages;
use App\Models\Galeri;
use App\Models\GaleriKategori;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Galeri';

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('judul')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            Select::make('galeri_kategori_id')
                ->label('Kategori')
                ->options(fn () => GaleriKategori::orderBy('urutan')->pluck('nama', 'id'))
                ->searchable()
                ->required(),

            FileUpload::make('foto')
                ->image()
                ->directory('galeri')
                ->required()
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Aktif Ditampilkan')
                ->default(true),

            TextInput::make('urutan')
                ->numeric()
                ->default(0)
                ->helperText('Urutan tampil di halaman Galeri, angka kecil di atas.'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->square(),
                TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('galeri_kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama'),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListGaleris::route('/'),
            'create' => Pages\CreateGaleri::route('/create'),
            'view' => Pages\ViewGaleri::route('/{record}'),
            'edit' => Pages\EditGaleri::route('/{record}/edit'),
        ];
    }
}