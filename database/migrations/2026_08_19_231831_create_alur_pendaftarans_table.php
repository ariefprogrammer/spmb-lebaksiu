<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alur_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 50); 
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alur_pendaftaran');
    }
};