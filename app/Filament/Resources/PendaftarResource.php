<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftarResource\Pages;
use App\Filament\Resources\PendaftarResource\RelationManagers;
use App\Models\Gelombang;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Pendaftar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftarResource extends Resource
{
    protected static ?string $model = Pendaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Pendaftar';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public const STATUS_PEMBAYARAN = [
        'belum_bayar' => 'Belum Bayar',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'terverifikasi' => 'Terverifikasi',
    ];

    public const STATUS_VERIFIKASI_BERKAS = [
        'menunggu' => 'Menunggu',
        'terverifikasi' => 'Terverifikasi',
        'ditolak' => 'Ditolak',
    ];

    public const HASIL_SELEKSI = [
        'menunggu' => 'Menunggu',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
    ];

    public const PENDIDIKAN = [
        'tidak-sekolah' => 'Tidak Sekolah',
        'sd' => 'SD',
        'smp' => 'SMP',
        'sma-smk' => 'SMA/SMK',
        'd3' => 'D3',
        's1' => 'S1',
        's2' => 'S2',
        's3' => 'S3',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pendaftaran')
                ->schema([
                    TextInput::make('no_pendaftaran')
                        ->label('No. Pendaftaran')
                        ->required()
                        ->maxLength(30)
                        ->unique(ignoreRecord: true)
                        ->helperText('Contoh: SPMB-2027-001'),

                    Select::make('gelombang_id')
                        ->label('Gelombang')
                        ->options(fn () => Gelombang::orderByDesc('tanggal_mulai')->pluck('nama', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('jurusan_id')
                        ->label('Kompetensi Keahlian')
                        ->options(fn () => Jurusan::where('is_active', true)->pluck('nama', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('akun_pendaftar_id')
                        ->label('Akun Pendaftar')
                        ->relationship('akunPendaftar', 'nama')
                        ->searchable()
                        ->nullable()
                        ->helperText('Kosongkan jika pendaftar belum/tidak memiliki akun.'),
                ])
                ->columns(2),

            Section::make('Data Pribadi')
                ->schema([
                    TextInput::make('nama_lengkap')
                        ->required()
                        ->maxLength(150)
                        ->columnSpanFull(),

                    Select::make('jenis_kelamin')
                        ->options([
                            'laki-laki' => 'Laki-laki',
                            'perempuan' => 'Perempuan',
                        ])
                        ->required(),

                    Select::make('agama')
                        ->options([
                            'islam' => 'Islam',
                            'kristen' => 'Kristen',
                            'katolik' => 'Katolik',
                            'hindu' => 'Hindu',
                            'buddha' => 'Buddha',
                            'konghucu' => 'Konghucu',
                            'lainnya' => 'Lainnya',
                        ])
                        ->required(),

                    TextInput::make('tempat_lahir')
                        ->required()
                        ->maxLength(100),

                    DatePicker::make('tanggal_lahir')
                        ->required(),

                    TextInput::make('asal_sekolah')
                        ->required()
                        ->maxLength(150)
                        ->columnSpanFull(),

                    TextInput::make('nisn')
                        ->label('NISN')
                        ->required()
                        ->length(10)
                        ->unique(ignoreRecord: true),

                    TextInput::make('nik')
                        ->label('NIK')
                        ->required()
                        ->length(16)
                        ->unique(ignoreRecord: true),

                    TextInput::make('anak_ke')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->columns(2),

            Section::make('Kontak & Alamat')
                ->schema([
                    TextInput::make('whatsapp_siswa')
                        ->label('WhatsApp Siswa')
                        ->tel()
                        ->required()
                        ->maxLength(20),

                    TextInput::make('email_siswa')
                        ->label('Email Siswa')
                        ->email()
                        ->maxLength(150)
                        ->nullable(),

                    Textarea::make('alamat_lengkap')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('desa_kelurahan')
                        ->label('Desa/Kelurahan')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('kecamatan')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('kabupaten')
                        ->required()
                        ->maxLength(100),
                ])
                ->columns(2),

            Section::make('Data Orang Tua / Wali')
                ->schema([
                    TextInput::make('nama_ibu')
                        ->required()
                        ->maxLength(150),

                    Select::make('pendidikan_ibu')
                        ->options(self::PENDIDIKAN)
                        ->required(),

                    TextInput::make('pekerjaan_ibu')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('nama_ayah')
                        ->required()
                        ->maxLength(150),

                    Select::make('pendidikan_ayah')
                        ->options(self::PENDIDIKAN)
                        ->required(),

                    TextInput::make('pekerjaan_ayah')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('whatsapp_ortu')
                        ->label('WhatsApp Orang Tua')
                        ->tel()
                        ->required()
                        ->maxLength(20),
                ])
                ->columns(2),

            Section::make('KIP & Rekomendasi Guru')
                ->schema([
                    Toggle::make('punya_kip')
                        ->label('Memiliki KIP')
                        ->live(),

                    TextInput::make('nomor_kip')
                        ->label('Nomor KIP')
                        ->maxLength(30)
                        ->visible(fn ($get) => $get('punya_kip'))
                        ->required(fn ($get) => $get('punya_kip')),

                    Select::make('rekomendasi_guru_id')
                        ->label('Rekomendasi Guru')
                        ->options(fn () => Guru::where('is_active', true)->pluck('nama', 'id'))
                        ->searchable()
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Status Proses')
                ->schema([
                    Select::make('status_pembayaran')
                        ->options(self::STATUS_PEMBAYARAN)
                        ->default('belum_bayar')
                        ->required(),

                    Select::make('status_verifikasi_berkas')
                        ->options(self::STATUS_VERIFIKASI_BERKAS)
                        ->default('menunggu')
                        ->required(),

                    Select::make('hasil_seleksi')
                        ->options(self::HASIL_SELEKSI)
                        ->default('menunggu')
                        ->required(),

                    Textarea::make('catatan_admin')
                        ->label('Catatan Panitia')
                        ->helperText('Ditampilkan ke pendaftar di halaman Cek Status.')
                        ->columnSpanFull(),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_pendaftaran')
                    ->label('No. Pendaftaran')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jurusan.nama')
                    ->label('Jurusan')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('gelombang.nama')
                    ->label('Gelombang')
                    ->sortable(),

                TextColumn::make('whatsapp_siswa')
                    ->label('WhatsApp')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUS_PEMBAYARAN[$state] ?? $state)
                    ->colors([
                        'gray' => 'belum_bayar',
                        'warning' => 'menunggu_verifikasi',
                        'success' => 'terverifikasi',
                    ]),

                TextColumn::make('status_verifikasi_berkas')
                    ->label('Berkas')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUS_VERIFIKASI_BERKAS[$state] ?? $state)
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'terverifikasi',
                        'danger' => 'ditolak',
                    ]),

                TextColumn::make('hasil_seleksi')
                    ->label('Hasil Seleksi')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::HASIL_SELEKSI[$state] ?? $state)
                    ->colors([
                        'gray' => 'menunggu',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                    ]),

                IconColumn::make('punya_kip')
                    ->label('KIP')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama'),

                SelectFilter::make('gelombang_id')
                    ->label('Gelombang')
                    ->relationship('gelombang', 'nama'),

                SelectFilter::make('status_pembayaran')
                    ->options(self::STATUS_PEMBAYARAN),

                SelectFilter::make('status_verifikasi_berkas')
                    ->label('Status Berkas')
                    ->options(self::STATUS_VERIFIKASI_BERKAS),

                SelectFilter::make('hasil_seleksi')
                    ->options(self::HASIL_SELEKSI),

                TernaryFilter::make('punya_kip')
                    ->label('Memiliki KIP'),
            ])
            ->actions([
                Action::make('verifikasiBerkas')
                    ->label('Verifikasi Berkas')
                    ->icon('heroicon-o-document-check')
                    ->color('success')
                    ->visible(fn (Pendaftar $record) => $record->status_verifikasi_berkas === 'menunggu')
                    ->requiresConfirmation()
                    ->modalDescription('Tandai berkas pendaftar ini sebagai lengkap dan terverifikasi?')
                    ->action(function (Pendaftar $record) {
                        $record->update(['status_verifikasi_berkas' => 'terverifikasi']);

                        Notification::make()
                            ->title('Berkas dinyatakan terverifikasi')
                            ->success()
                            ->send();
                    }),

                Action::make('tolakBerkas')
                    ->label('Tolak Berkas')
                    ->icon('heroicon-o-document-minus')
                    ->color('danger')
                    ->visible(fn (Pendaftar $record) => $record->status_verifikasi_berkas === 'menunggu')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan_admin')
                            ->label('Alasan Penolakan Berkas')
                            ->required(),
                    ])
                    ->action(function (Pendaftar $record, array $data) {
                        $record->update([
                            'status_verifikasi_berkas' => 'ditolak',
                            'catatan_admin' => $data['catatan_admin'],
                        ]);

                        Notification::make()
                            ->title('Berkas ditolak')
                            ->warning()
                            ->send();
                    }),

                Action::make('tentukanHasil')
                    ->label('Tentukan Hasil Seleksi')
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    ->visible(fn (Pendaftar $record) => $record->status_verifikasi_berkas === 'terverifikasi'
                        && $record->status_pembayaran === 'terverifikasi'
                        && $record->hasil_seleksi === 'menunggu')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('hasil_seleksi')
                            ->label('Hasil')
                            ->options([
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required(),
                        Textarea::make('catatan_admin')
                            ->label('Catatan untuk Pendaftar')
                            ->helperText('Muncul di halaman Cek Status, mis. instruksi daftar ulang.'),
                    ])
                    ->action(function (Pendaftar $record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $record->update([
                                'hasil_seleksi' => $data['hasil_seleksi'],
                                'catatan_admin' => $data['catatan_admin'] ?? $record->catatan_admin,
                            ]);
                        });

                        Notification::make()
                            ->title('Hasil seleksi tersimpan')
                            ->success()
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
        return static::getModel()::where('hasil_seleksi', 'menunggu')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PembayaranRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftars::route('/'),
            'create' => Pages\CreatePendaftar::route('/create'),
            'view' => Pages\ViewPendaftar::route('/{record}'),
            'edit' => Pages\EditPendaftar::route('/{record}/edit'),
        ];
    }
}