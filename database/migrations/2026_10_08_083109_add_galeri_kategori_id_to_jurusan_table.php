<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\GaleriKategori;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->foreignId('galeri_kategori_id')
                ->nullable()
                ->after('kaprodi_guru_id')
                ->constrained((new GaleriKategori)->getTable())
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('galeri_kategori_id');
        });
    }
};
