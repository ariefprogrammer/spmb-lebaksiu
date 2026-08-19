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

    // Daftar key yang dikelola halaman ini -- tambah di sini kalau perlu field baru.
    public const KEYS = [
        'tahun_ajaran',
        'kontak_telepon',
        'kontak_email',
        'kontak_whatsapp_cs',
        'alamat_sekolah',
        'sosmed_instagram',
        'sosmed_facebook',
        'sosmed_youtube',
        'teks_pengumuman',
        'logo_sekolah',
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
                    ])->columns(2),

                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('sosmed_instagram')
                            ->label('Instagram')
                            ->url()
                            ->placeholder('https://instagram.com/...'),

                        TextInput::make('sosmed_facebook')
                            ->label('Facebook')
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