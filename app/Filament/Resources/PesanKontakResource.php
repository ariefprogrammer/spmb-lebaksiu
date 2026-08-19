<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesanKontakResource\Pages;
use App\Models\PesanKontak;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PesanKontakResource extends Resource
{
    protected static ?string $model = PesanKontak::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Pesan Kontak';

    protected static ?string $recordTitleAttribute = 'nama';

    // Read-only: form ini hanya dipakai halaman View, tidak ada Create/Edit.
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->disabled(),
            TextInput::make('whatsapp')
                ->disabled(),
            TextInput::make('email')
                ->disabled(),
            TextInput::make('subjek')
                ->disabled()
                ->columnSpanFull(),
            Textarea::make('pesan')
                ->disabled()
                ->rows(5)
                ->columnSpanFull(),
            Toggle::make('is_read')
                ->label('Sudah Dibaca'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('')
                    ->icon(fn (bool $state) => $state ? 'heroicon-o-envelope-open' : 'heroicon-s-envelope')
                    ->color(fn (bool $state) => $state ? 'gray' : 'warning'),
                TextColumn::make('nama')
                    ->searchable()
                    ->weight(fn (PesanKontak $record) => $record->is_read ? null : 'bold')
                    ->sortable(),
                TextColumn::make('subjek')
                    ->searchable()
                    ->weight(fn (PesanKontak $record) => $record->is_read ? null : 'bold')
                    ->limit(40),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->copyable(),
                TextColumn::make('email')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Status Baca')
                    ->trueLabel('Sudah dibaca')
                    ->falseLabel('Belum dibaca'),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),

                Action::make('toggleRead')
                    ->label(fn (PesanKontak $record) => $record->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca')
                    ->icon(fn (PesanKontak $record) => $record->is_read ? 'heroicon-o-envelope' : 'heroicon-o-envelope-open')
                    ->color('gray')
                    ->action(function (PesanKontak $record) {
                        $record->update(['is_read' => ! $record->is_read]);
                    }),

                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_read', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanKontaks::route('/'),
            'view' => Pages\ViewPesanKontak::route('/{record}'),
        ];
    }
}