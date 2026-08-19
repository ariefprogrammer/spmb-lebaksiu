<?php

namespace App\Filament\Resources\PendaftarResource\RelationManagers;

use App\Models\Pembayaran;
use App\Models\Rekening;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';

    protected static ?string $title = 'Riwayat Pembayaran';

    protected static ?string $recordTitleAttribute = 'bank_pengirim';

    public const STATUS = [
        'menunggu' => 'Menunggu',
        'terverifikasi' => 'Terverifikasi',
        'ditolak' => 'Ditolak',
    ];

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('rekening_id')
                ->label('Rekening Tujuan')
                ->options(fn () => Rekening::pluck('nama_bank', 'id'))
                ->searchable()
                ->nullable(),

            TextInput::make('bank_pengirim')
                ->required()
                ->maxLength(50),

            DatePicker::make('tanggal_transfer')
                ->required(),

            TextInput::make('nominal_transfer')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            FileUpload::make('bukti_transfer_path')
                ->label('Bukti Transfer')
                ->image()
                ->directory('bukti-transfer')
                ->required()
                ->columnSpanFull(),

            Select::make('status')
                ->options(self::STATUS)
                ->default('menunggu')
                ->required(),

            Textarea::make('catatan')
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('bukti_transfer_path')
                    ->label('Bukti')
                    ->square(),

                TextColumn::make('bank_pengirim')
                    ->label('Bank Pengirim'),

                TextColumn::make('nominal_transfer')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tanggal_transfer')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUS[$state] ?? $state)
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'terverifikasi',
                        'danger' => 'ditolak',
                    ]),

                TextColumn::make('verifiedBy.name')
                    ->label('Diverifikasi Oleh')
                    ->placeholder('-'),

                TextColumn::make('verified_at')
                    ->label('Waktu Verifikasi')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::STATUS),
            ])
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pembayaran $record) => $record->status === 'menunggu')
                    ->requiresConfirmation()
                    ->modalDescription('Tandai transfer ini sebagai terverifikasi? Status pembayaran pendaftar akan ikut terupdate.')
                    ->action(function (Pembayaran $record) {
                        DB::transaction(function () use ($record) {
                            $record->update([
                                'status' => 'terverifikasi',
                                'verified_by_user_id' => Auth::id(),
                                'verified_at' => now(),
                            ]);

                            $record->pendaftar()->update([
                                'status_pembayaran' => 'terverifikasi',
                            ]);
                        });

                        Notification::make()
                            ->title('Pembayaran terverifikasi')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Pembayaran $record) => $record->status === 'menunggu')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Pembayaran $record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $record->update([
                                'status' => 'ditolak',
                                'catatan' => $data['catatan'],
                                'verified_by_user_id' => Auth::id(),
                                'verified_at' => now(),
                            ]);

                            $record->pendaftar()->update([
                                'status_pembayaran' => 'belum_bayar',
                            ]);
                        });

                        Notification::make()
                            ->title('Pembayaran ditolak')
                            ->warning()
                            ->send();
                    }),

                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}