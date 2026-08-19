<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GelombangResource\Pages;
use App\Models\Gelombang;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GelombangResource extends Resource
{
    protected static ?string $model = Gelombang::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Gelombang';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->required()
                ->maxLength(100)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->required()
                ->maxLength(120)
                ->unique(ignoreRecord: true)
                ->helperText('Otomatis terisi dari Nama, boleh diubah manual.'),

            TextInput::make('harga_formulir')
                ->label('Harga Formulir')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            DatePicker::make('tanggal_mulai')
                ->required()
                ->live(),

            DatePicker::make('tanggal_selesai')
                ->required()
                ->afterOrEqual('tanggal_mulai'),

            TagsInput::make('benefit')
                ->label('Benefit (bullet list)')
                ->placeholder('Ketik lalu Enter untuk tiap poin, mis. Potongan 40%')
                ->columnSpanFull(),

            TextInput::make('ribbon_text')
                ->label('Teks Ribbon')
                ->helperText('Contoh: "Hemat 40%", muncul sebagai label kecil di card gelombang.')
                ->maxLength(50),

            Toggle::make('is_highlight')
                ->label('Tandai sebagai Gelombang Unggulan')
                ->helperText('Card gelombang ini ditonjolkan di halaman depan.'),

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
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harga_formulir')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status_periode')
                    ->label('Status')
                    ->state(function (Gelombang $record) {
                        $today = now()->toDateString();

                        return match (true) {
                            $today < $record->tanggal_mulai->toDateString() => 'Akan Datang',
                            $today > $record->tanggal_selesai->toDateString() => 'Berakhir',
                            default => 'Sedang Berlangsung',
                        };
                    })
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Sedang Berlangsung' => 'success',
                        'Akan Datang' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_highlight')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('pendaftar_count')
                    ->label('Jumlah Pendaftar')
                    ->counts('pendaftar')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('urutan')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_highlight')
                    ->label('Gelombang Unggulan'),
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
            'index' => Pages\ListGelombangs::route('/'),
            'create' => Pages\CreateGelombang::route('/create'),
            'view' => Pages\ViewGelombang::route('/{record}'),
            'edit' => Pages\EditGelombang::route('/{record}/edit'),
        ];
    }
}