<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran', 30)->unique(); // 'SPMB-2027-00123'

            $table->foreignId('akun_pendaftar_id')->nullable()->constrained('akun_pendaftar')->nullOnDelete();
            $table->foreignId('gelombang_id')->constrained('gelombang')->restrictOnDelete();
            $table->foreignId('jurusan_id')->constrained('jurusan')->restrictOnDelete();
            $table->foreignId('rekomendasi_guru_id')->nullable()->constrained('guru')->nullOnDelete();

            // A. Data Pribadi
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'lainnya']);
            $table->string('asal_sekolah', 150);
            $table->char('nisn', 10)->unique();
            $table->char('nik', 16)->unique();
            $table->unsignedTinyInteger('anak_ke');

            // Kontak & alamat
            $table->string('whatsapp_siswa', 20);
            $table->string('email_siswa', 150)->nullable();
            $table->text('alamat_lengkap');
            $table->string('desa_kelurahan', 100);
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);

            // C. Data Orang Tua/Wali
            $table->string('nama_ibu', 150);
            $table->enum('pendidikan_ibu', ['tidak-sekolah', 'sd', 'smp', 'sma-smk', 'd3', 's1', 's2', 's3']);
            $table->string('pekerjaan_ibu', 100);
            $table->string('nama_ayah', 150);
            $table->enum('pendidikan_ayah', ['tidak-sekolah', 'sd', 'smp', 'sma-smk', 'd3', 's1', 's2', 's3']);
            $table->string('pekerjaan_ayah', 100);
            $table->string('whatsapp_ortu', 20);

            // D. Data KIP
            $table->boolean('punya_kip')->default(false);
            $table->string('nomor_kip', 30)->nullable();

            // Status proses -- dipakai halaman Cek Status
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'terverifikasi'])->default('belum_bayar');
            $table->enum('status_verifikasi_berkas', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->enum('hasil_seleksi', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();

            $table->timestamps(); // created_at = waktu "Formulir Terkirim"

            $table->index(
                ['status_pembayaran', 'status_verifikasi_berkas', 'hasil_seleksi'],
                'pendaftar_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};
