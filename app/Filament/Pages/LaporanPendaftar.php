<?php
namespace App\Filament\Pages;

use App\Exports\PendaftarExport;
use App\Models\Pendaftar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPendaftar extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Data Pendaftar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.laporan-pendaftar';

    // Property untuk menampung data form filter secara real-time
    public ?array $filterData = [];

    public function mount(): void
    {
        // Inisialisasi form filter saat halaman dimuat
        $this->form->fill();
    }

    public function getTitle(): string
    {
        return 'Laporan Data Pendaftar';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(5) // Diubah jadi 5 kolom agar pas jika ingin 1 baris
                    ->schema([
                        Select::make('jurusan_id')
                            ->label('Jurusan')
                            ->options(\App\Models\Jurusan::pluck('nama', 'id'))
                            ->placeholder('Semua Jurusan'),

                        Select::make('gelombang_id')
                            ->label('Gelombang')
                            ->options(\App\Models\Gelombang::pluck('nama', 'id'))
                            ->placeholder('Semua Gelombang'),

                        Select::make('hasil_seleksi')
                            ->label('Hasil Seleksi')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->placeholder('Semua Status'),

                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),

                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ]),
            ])
            ->statePath('filterData');
    }

    // Method khusus yang dipanggil saat tombol "Tampilkan Data" ditekan
    public function submitFilter(): void
    {
        // Menyegarkan state tabel agar mengambil query filterData terbaru
        $this->resetTable();
    }

    // Helper untuk membuat Query dasar yang sudah terfilter
    protected function getFilteredQuery()
    {
        $query = Pendaftar::query()->with(['jurusan', 'gelombang']);

        if (!empty($this->filterData['jurusan_id'])) {
            $query->where('jurusan_id', $this->filterData['jurusan_id']);
        }

        if (!empty($this->filterData['gelombang_id'])) {
            $query->where('gelombang_id', $this->filterData['gelombang_id']);
        }

        if (!empty($this->filterData['hasil_seleksi'])) {
            $query->where('hasil_seleksi', $this->filterData['hasil_seleksi']);
        }

        if (!empty($this->filterData['created_from'])) {
            $query->whereDate('created_at', '>=', $this->filterData['created_from']);
        }

        if (!empty($this->filterData['created_until'])) {
            $query->whereDate('created_at', '<=', $this->filterData['created_until']);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            // Menggunakan query terfilter sebagai source data tabel
            ->query(
                $this->getFilteredQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('no_pendaftaran')
                    ->label('No. Pendaftaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->formatStateUsing(fn (string $state): string => $state === 'laki-laki' ? 'L' : 'P')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('jurusan.nama')
                    ->label('Jurusan')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gelombang.nama')
                    ->label('Gelombang')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('whatsapp_siswa')
                    ->label('WhatsApp')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->badge(),

                Tables\Columns\TextColumn::make('status_verifikasi_berkas')
                    ->label('Berkas')
                    ->badge(),

                Tables\Columns\TextColumn::make('hasil_seleksi')
                    ->label('Hasil Seleksi')
                    ->badge(),

                Tables\Columns\IconColumn::make('daftar_ulang_at')
                    ->label('Daftar Ulang')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        // Memanggil helper query yang sama persis seperti tabel
                        $query = $this->getFilteredQuery()->orderBy('created_at');

                        return Excel::download(
                            new PendaftarExport($query),
                            'laporan-pendaftar-' . now()->format('Y-m-d-His') . '.xlsx'
                        );
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}