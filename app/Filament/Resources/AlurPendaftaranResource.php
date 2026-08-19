<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlurPendaftaranResource\Pages;
use App\Models\AlurPendaftaran;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AlurPendaftaranResource extends Resource
{
    protected static ?string $model = AlurPendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Alur Pendaftaran';

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('icon')
                ->label('Icon')
                ->required()
                ->maxLength(50)
                ->placeholder('bi-person-plus-fill')
                ->helperText('Nama class dari Bootstrap Icons. Cari di icons.getbootstrap.com, salin nama class-nya (contoh: bi-person-plus-fill).')
                ->columnSpanFull(),

            TextInput::make('judul')
                ->required()
                ->maxLength(100)
                ->columnSpanFull(),

            Textarea::make('deskripsi')
                ->required()
                ->rows(3)
                ->columnSpanFull(),

            TextInput::make('urutan')
                ->numeric()
                ->default(0)
                ->helperText('Urutan tampil sebagai langkah ke berapa, angka kecil di depan.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('icon')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deskripsi')
                    ->limit(60)
                    ->wrap(),
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
            'index' => Pages\ListAlurPendaftarans::route('/'),
            'create' => Pages\CreateAlurPendaftaran::route('/create'),
            'view' => Pages\ViewAlurPendaftaran::route('/{record}'),
            'edit' => Pages\EditAlurPendaftaran::route('/{record}/edit'),
        ];
    }
}