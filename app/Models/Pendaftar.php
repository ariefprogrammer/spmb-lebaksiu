<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    protected $fillable = [
        'no_pendaftaran',
        'akun_pendaftar_id',
        'gelombang_id',
        'jurusan_id',
        'rekomendasi_guru_id',

        // A. Data Pribadi
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'asal_sekolah',
        'nisn',
        'nik',
        'anak_ke',

        // Kontak & alamat
        'whatsapp_siswa',
        'email_siswa',
        'alamat_lengkap',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten',

        // C. Data Orang Tua/Wali
        'nama_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'nama_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'whatsapp_ortu',

        // D. Data KIP
        'punya_kip',
        'nomor_kip',

        // Status proses
        'status_pembayaran',
        'status_verifikasi_berkas',
        'hasil_seleksi',
        'catatan_admin',
        'catatan_link',
        'daftar_ulang_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'anak_ke' => 'integer',
            'punya_kip' => 'boolean',
            'daftar_ulang_at' => 'datetime',
        ];
    }

    public function akunPendaftar(): BelongsTo
    {
        return $this->belongsTo(AkunPendaftar::class);
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function rekomendasiGuru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'rekomendasi_guru_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function pembayaranTerbaru(): HasOne
    {
        return $this->hasOne(Pembayaran::class)->latestOfMany();
    }

    public function catatanUntukPendaftar(): string
    {
        if (! empty($this->catatan_admin)) {
            return $this->catatan_admin;
        }

        if ($this->hasil_seleksi === 'diterima') {
            return $this->catatan_link
                ? 'Selamat! Kamu dinyatakan diterima. Silakan join grup WhatsApp di bawah ini untuk informasi daftar ulang selanjutnya.'
                : 'Selamat! Kamu dinyatakan diterima. Silakan tunggu informasi jadwal daftar ulang dari panitia.';
        }

        if ($this->hasil_seleksi === 'ditolak') {
            return 'Mohon maaf, kamu belum berhasil pada seleksi kali ini. Terima kasih atas partisipasimu.';
        }

        if ($this->status_pembayaran === 'terverifikasi' && $this->status_verifikasi_berkas === 'terverifikasi') {
            return 'Pembayaran dan berkas kamu sudah terverifikasi. Tunggu pengumuman hasil seleksi ya.';
        }

        if (in_array($this->status_pembayaran, ['menunggu_verifikasi'], true)) {
            return 'Silahkan upload bukti transfer untuk diverifikasi oleh panitia, maksimal 2x24 jam kerja.';
        }

        if (in_array($this->status_pembayaran, ['terverifikasi'], true)) {
            return 'Bukti transfer sudah terverifikasi dan sedang proses verifikasi berkas.';
        }

        return 'Formulir kamu sudah kami terima. Segera lakukan pembayaran dan konfirmasi transfer agar proses verifikasi bisa dilanjutkan.';
    }
}
