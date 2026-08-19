<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 170)->unique();
            $table->text('deskripsi')->nullable();
            $table->json('keunggulan')->nullable(); // array string, bullet list di card Profil Sekolah
            $table->string('icon', 60)->nullable();
            $table->string('foto')->nullable();
            $table->string('akreditasi', 5)->nullable();

            // FK ke guru ditambahkan di migration terpisah (2025_01_01_000006)
            // setelah tabel guru dibuat -- menghindari circular reference.
            $table->unsignedBigInteger('kaprodi_guru_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurusan');
    }
};
