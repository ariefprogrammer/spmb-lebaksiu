<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\Pendaftar;
use App\Models\Rekening;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';

    public const STATUS = [
        'menunggu' => 'Menunggu',
        'terverifikasi' => 'Terverifikasi',
        'ditolak' => 'Ditolak',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('pendaftar_id')
                ->label('Pendaftar')
                ->options(fn () => Pendaftar::query()
                    ->orderByDesc('created_at')
                    ->limit(100)
                    ->get()
                    ->mapWithKeys(fn (Pendaftar $p) => [$p->id => "{$p->no_pendaftaran} — {$p->nama_lengkap}"]))
                ->searchable()
                ->getSearchResultsUsing(fn (string $search) => Pendaftar::query()
                    ->where('no_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(fn (Pendaftar $p) => [$p->id => "{$p->no_pendaftaran} — {$p->nama_lengkap}"]))
                ->required(),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pendaftar.no_pendaftaran')
                    ->label('No. Pendaftaran')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('pendaftar.nama_lengkap')
                    ->label('Nama')
                    ->searchable(),
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
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::STATUS),
                SelectFilter::make('rekening_id')
                    ->label('Rekening')
                    ->relationship('rekening', 'nama_bank'),
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

                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
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
        return static::getModel()::where('status', 'menunggu')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'view' => Pages\ViewPembayaran::route('/{record}'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}