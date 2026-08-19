<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurusanResource\Pages;
use App\Models\Guru;
use App\Models\Jurusan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Jurusan';

    protected static ?string $recordTitleAttribute = 'nama';

    public const AKREDITASI = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'Belum Terakreditasi' => 'Belum Terakreditasi',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->required()
                ->maxLength(150)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->required()
                ->maxLength(170)
                ->unique(ignoreRecord: true)
                ->helperText('Otomatis terisi dari Nama, boleh diubah manual.'),

            Textarea::make('deskripsi')
                ->rows(4)
                ->columnSpanFull(),

            TagsInput::make('keunggulan')
                ->label('Keunggulan (bullet list)')
                ->placeholder('Ketik lalu Enter untuk tiap poin')
                ->columnSpanFull(),

            TextInput::make('icon')
                ->helperText('Nama icon Bootstrap Icons, mis. bi-capsule')
                ->maxLength(60),

            Select::make('akreditasi')
                ->options(self::AKREDITASI)
                ->native(false),

            Select::make('kaprodi_guru_id')
                ->label('Kepala Program (Kaprodi)')
                ->options(fn () => Guru::orderBy('nama')->pluck('nama', 'id'))
                ->searchable()
                ->nullable(),

            FileUpload::make('foto')
                ->image()
                ->directory('jurusan')
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Aktif Ditampilkan')
                ->default(true),

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
                    ->square(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('akreditasi')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'A' => 'success',
                        'B' => 'warning',
                        'C' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('kaprodi.nama')
                    ->label('Kaprodi')
                    ->placeholder('-'),
                TextColumn::make('guru_count')
                    ->label('Jumlah Guru')
                    ->counts('guru')
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
            'index' => Pages\ListJurusans::route('/'),
            'create' => Pages\CreateJurusan::route('/create'),
            'view' => Pages\ViewJurusan::route('/{record}'),
            'edit' => Pages\EditJurusan::route('/{record}/edit'),
        ];
    }
}