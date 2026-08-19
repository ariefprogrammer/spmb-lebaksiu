<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Konten Sekolah';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $recordTitleAttribute = 'pertanyaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('pertanyaan')
                ->required()
                ->maxLength(200)
                ->columnSpanFull(),

            Textarea::make('jawaban')
                ->required()
                ->rows(4)
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Aktif Ditampilkan')
                ->default(true),

            TextInput::make('urutan')
                ->numeric()
                ->default(0)
                ->helperText('Urutan tampil di accordion, angka kecil di atas.'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('pertanyaan')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('jawaban')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'view' => Pages\ViewFaq::route('/{record}'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}