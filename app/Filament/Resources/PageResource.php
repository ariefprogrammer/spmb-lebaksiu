<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'Halaman Informasi';

    protected static ?string $recordTitleAttribute = 'title';

    public const STATUS = [
        'draft' => 'Draft',
        'published' => 'Published',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Konten')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(200)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(220)
                        ->unique(ignoreRecord: true)
                        ->helperText('Otomatis terisi dari Judul, boleh diubah manual. Dipakai di URL: halaman.php?slug=...'),

                    Textarea::make('excerpt')
                        ->label('Ringkasan')
                        ->maxLength(255)
                        ->rows(2)
                        ->helperText('Ditampilkan di kartu/menu dropdown Informasi.')
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Isi Halaman')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('SEO')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(200),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->maxLength(255)
                        ->rows(2),
                ])->columns(2)
                ->collapsible(),

            Section::make('Publikasi')
                ->schema([
                    Select::make('status')
                        ->options(self::STATUS)
                        ->default('draft')
                        ->required()
                        ->live(),

                    DateTimePicker::make('published_at')
                        ->label('Tanggal Terbit')
                        ->visible(fn ($get) => $get('status') === 'published')
                        ->default(now()),

                    Toggle::make('show_in_menu')
                        ->label('Tampilkan di Menu Informasi')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0)
                        ->helperText('Urutan tampil di menu Informasi, angka kecil di atas.'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUS[$state] ?? $state)
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                    ]),
                IconColumn::make('show_in_menu')
                    ->label('Di Menu')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Tgl Terbit')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::STATUS),
                TernaryFilter::make('show_in_menu')
                    ->label('Tampil di Menu'),
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
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}