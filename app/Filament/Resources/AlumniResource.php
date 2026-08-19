<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlumniResource\Pages;
use App\Models\Alumni;
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

class AlumniResource extends Resource
{
    protected static ?string $model = Alumni::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Alumni';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->options(fn () => Jurusan::orderBy('urutan')->pluck('nama', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('tahun_lulus')
                ->numeric()
                ->required()
                ->minValue(2000)
                ->maxValue((int) date('Y') + 1),

            TextInput::make('tempat_kerja')
                ->label('Tempat Kerja / Melanjutkan Studi')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            Toggle::make('is_featured')
                ->label('Tampilkan di Halaman Depan')
                ->helperText('Aktifkan untuk menampilkan alumni ini di bagian testimoni/highlight homepage.'),

            FileUpload::make('foto')
                ->image()
                ->avatar()
                ->directory('alumni')
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
                TextColumn::make('jurusan.nama')
                    ->label('Jurusan')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->sortable(),
                TextColumn::make('tempat_kerja')
                    ->label('Tempat Kerja')
                    ->searchable()
                    ->limit(40),
                IconColumn::make('is_featured')
                    ->label('Tampil di Homepage')
                    ->boolean(),
                TextColumn::make('urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama'),
                SelectFilter::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->options(fn () => Alumni::query()
                        ->distinct()
                        ->orderByDesc('tahun_lulus')
                        ->pluck('tahun_lulus', 'tahun_lulus')),
                TernaryFilter::make('is_featured')
                    ->label('Tampil di Homepage'),
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
            'index' => Pages\ListAlumnis::route('/'),
            'create' => Pages\CreateAlumni::route('/create'),
            'view' => Pages\ViewAlumni::route('/{record}'),
            'edit' => Pages\EditAlumni::route('/{record}/edit'),
        ];
    }
}