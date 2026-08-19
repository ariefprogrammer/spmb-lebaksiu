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
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'anak_ke' => 'integer',
            'punya_kip' => 'boolean',
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

    /**
     * Bukti transfer paling baru yang diunggah pendaftar -- dipakai halaman
     * Cek Status & Filament untuk menampilkan status transaksi terkini,
     * mengingat satu pendaftar bisa mengunggah ulang bukti transfer.
     */
    public function pembayaranTerbaru(): HasOne
    {
        return $this->hasOne(Pembayaran::class)->latestOfMany();
    }
}
