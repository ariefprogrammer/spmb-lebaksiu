<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->foreignId('jurusan_id')->constrained('jurusan')->restrictOnDelete();
            $table->year('tahun_lulus');
            $table->string('tempat_kerja', 150);
            $table->string('foto')->nullable();
            $table->boolean('is_featured')->default(false); // true = tampil di homepage
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
