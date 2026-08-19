<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 60); // 'Kegiatan Belajar', 'Ekstrakurikuler', dst
            $table->string('slug', 70)->unique();
            $table->unsignedSmallInteger('urutan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_kategori');
    }
};
