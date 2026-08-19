<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Models\Guru;
use App\Models\Jurusan;
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

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Guru';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            TextInput::make('mapel')
                ->label('Mata Pelajaran')
                ->maxLength(150),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->options(fn () => Jurusan::orderBy('urutan')->pluck('nama', 'id'))
                ->searchable()
                ->nullable()
                ->helperText('Kosongkan jika guru mapel normatif/tidak terikat satu jurusan.'),

            TextInput::make('jabatan')
                ->maxLength(100)
                ->helperText('Contoh: Kaprodi TKJ, Wali Kelas X, Kepala Sekolah.'),

            Toggle::make('is_pimpinan')
                ->label('Pimpinan Sekolah')
                ->helperText('Aktifkan untuk Kepala Sekolah / Wakil Kepala Sekolah — tampil di posisi atas grid Guru.')
                ->live(),

            Toggle::make('is_active')
                ->label('Aktif Ditampilkan')
                ->default(true),

            FileUpload::make('foto')
                ->image()
                ->avatar()
                ->directory('guru')
                ->columnSpanFull(),

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
                ImageColumn::make('foto')
                    ->circular(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('jurusan.nama')
                    ->label('Jurusan')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('jabatan')
                    ->placeholder('-'),
                IconColumn::make('is_pimpinan')
                    ->label('Pimpinan')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama'),
                TernaryFilter::make('is_pimpinan')
                    ->label('Pimpinan Sekolah'),
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'view' => Pages\ViewGuru::route('/{record}'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}