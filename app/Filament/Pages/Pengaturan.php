<?php

namespace App\Filament\Pages;

use App\Models\Pengaturan as PengaturanModel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Pengaturan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan Website';

    protected static string $view = 'filament.pages.pengaturan';

    public const KEYS = [
        'tahun_ajaran',
        'kontak_telepon',
        'kontak_email',
        'kontak_whatsapp_cs',
        'alamat_sekolah',
        'jam_operasional_weekday',
        'jam_operasional_sabtu',
        'sosmed_instagram',
        'sosmed_tiktok',
        'sosmed_youtube',
        'teks_pengumuman',
        'logo_sekolah',
        'profil_tahun_berdiri',
        'profil_deskripsi',
        'serbaserbi_persentase_terserap',
        'serbaserbi_jumlah_mitra',
    ];

    public ?array $data = [];

    public function mount(): void
    {
        $values = [];

        foreach (self::KEYS as $key) {
            $values[$key] = PengaturanModel::where('key', $key)->value('value');
        }

        $this->form->fill($values);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Sekolah')
                    ->schema([
                        TextInput::make('tahun_ajaran')
                            ->label('Tahun Ajaran')
                            ->placeholder('2027/2028')
                            ->required(),

                        FileUpload::make('logo_sekolah')
                            ->label('Logo Sekolah')
                            ->image()
                            ->directory('pengaturan'),
                    ])->columns(2),

                Section::make('Profil Sekolah')
                    ->schema([
                        TextInput::make('profil_tahun_berdiri')
                            ->label('Tahun Berdiri')
                            ->numeric()
                            ->placeholder('1998'),

                        Textarea::make('profil_deskripsi')
                            ->label('Deskripsi Profil')
                            ->rows(4)
                            ->helperText('Ditampilkan di section "Mengenal Sekolah" pada halaman depan.')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Statistik Serba-serbi SPMB')
                    ->schema([
                        TextInput::make('serbaserbi_persentase_terserap')
                            ->label('Persentase Alumni Terserap Kerja')
                            ->placeholder('86%')
                            ->helperText('Angka kurasi manual, tidak dihitung otomatis dari data.'),

                        TextInput::make('serbaserbi_jumlah_mitra')
                            ->label('Jumlah Perusahaan & Instansi Mitra')
                            ->placeholder('40+'),
                    ])->columns(2),

                Section::make('Kontak')
                    ->schema([
                        TextInput::make('kontak_telepon')
                            ->label('Telepon')
                            ->tel()
                            ->placeholder('(0283) 123-4567'),

                        TextInput::make('kontak_email')
                            ->label('Email')
                            ->email(),

                        TextInput::make('kontak_whatsapp_cs')
                            ->label('WhatsApp CS/Panitia')
                            ->tel()
                            ->helperText('Nomor tujuan tombol WhatsApp di halaman Kontak, format 62xxxxxxxxxx.'),

                        Textarea::make('alamat_sekolah')
                            ->label('Alamat Sekolah')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('jam_operasional_weekday')
                            ->label('Jam Operasional (Senin-Jumat)')
                            ->placeholder('Senin – Jumat: 07.00 – 15.00 WIB'),

                        TextInput::make('jam_operasional_sabtu')
                            ->label('Jam Operasional (Sabtu)')
                            ->placeholder('Sabtu: 08.00 – 12.00 WIB'),
                    ])->columns(2),

                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('sosmed_instagram')
                            ->label('Instagram')
                            ->url()
                            ->placeholder('https://instagram.com/...'),

                        TextInput::make('sosmed_tiktok')
                            ->label('Tiktok')
                            ->url(),

                        TextInput::make('sosmed_youtube')
                            ->label('YouTube')
                            ->url(),
                    ])->columns(3),

                Section::make('Pengumuman')
                    ->schema([
                        Textarea::make('teks_pengumuman')
                            ->label('Teks Pengumuman Topbar')
                            ->rows(2)
                            ->helperText('Contoh: "Gelombang 2 resmi dibuka — kuota terbatas!"')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $values = $this->form->getState();

        foreach ($values as $key => $value) {
            PengaturanModel::set($key, $value);
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}